<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Permintaan dengan metode HTTP yang tidak didukung rute (misalnya DELETE
        // dikirim ke halaman yang hanya menerima GET) tidak perlu menampilkan
        // halaman error teknis — cukup alihkan pengguna kembali dengan pesan.
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Metode permintaan tidak didukung.'], 405);
            }

            $target = $request->headers->get('referer') ?: url('/');

            return redirect()->away($target)->with(
                'error',
                'Permintaan tidak valid: metode HTTP tidak didukung untuk halaman tersebut. Silakan coba lagi dari menu yang tersedia.'
            );
        });
    }
}
