<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

	/**
	 * Return "checked" if true
	 */
	static function checked($value)
	{
		return $value ? 'checked' : '';
	}

	/**
	 * Return img url for headers
	 */
	static function page_image($value = null)
	{
		if (empty($value)) {
			$value = config('blog.page_image');
		}
		if (! starts_with($value, 'http') && $value[0] !== '/') {
			$value = config('blog.uploads.webpath') . '/' . $value;
		}

		return $value;
	}
}
