<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {//유효성 검사 예외
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {//모델 예외
            $modelName = $exception->getModel();

            return $this->errorResponse('Model '.$modelName.' is not found.', 404);
        } 

        if ($exception instanceof AuthenticationException) {//인증 예외
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {//권한 예외
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if ($exception instanceof NotFoundHttpException) {//URL 예외
            return $this->errorResponse('The specified URL cannot be found.', 404);
        }    
        
        if ($exception instanceof MethodNotAllowedHttpException) {//METHOD 예외
            return $this->errorResponse('The specified method for the request is invalid.', 405);
        }  

        if ($exception instanceof HttpException) {//모든 http 요청 예외
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        } 

        if ($exception instanceof QueryException) {//쿼리 예외
            $errorCode = $exception->errorInfo[1];

            if($errorCode == 1451){
                return $this->errorResponse('Cannot remove this resource permanetly. It is related with any other resource.', 409);
            }
        } 

        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected Exception. Try later', 500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse($exception->getMessage(), 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        if ($exception->response) {
            return $exception->response;
        }
        
        return $this->errorResponse($exception->errors(), 422);
    }
}
