<?php
use yii\web\NotFoundHttpException;
/**
 * @var Exception $exception
 * @var string $message
 * @var string $name
 */
?>
<?php
if ($exception->getCode() === 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 Not Found</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body, html {
                height: 100%;
            }
            .bg-404 {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                text-align: center;
                position: relative;
            }
            .error-number {
                font-size: 10rem;
                font-weight: bold;
                color: #343a40;
                animation: float 2s infinite;
            }
            .error-message {
                font-size: 1.5rem;
                color: #6c757d;
                animation: fade 2s infinite;
            }
            .btn-home {
                margin-top: 20px;
            }
            .dino {
                position: absolute;
                bottom: 20px;
                width: 150px;
                height: auto;
                animation: run 1s steps(4) infinite;
            }
            @keyframes float {
                0% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
                100% {
                    transform: translateY(0);
                }
            }
            @keyframes fade {
                0% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
                100% {
                    opacity: 1;
                }
            }
            @keyframes run {
                from {
                    background-position: 0;
                }
                to {
                    background-position: -600px;
                }
            }
        </style>
    </head>
    <body>
    <div class="bg-404">
        <div>
            <div class="error-number">404</div>
            <div class="error-message">Страница не найдена</div>
            <p>Извините, но страница, которую вы ищете, не существует. Возможно, она была перемещена или удалена.</p>
            <a href="/" class="btn btn-primary btn-home">Вернуться на главную</a>
        </div>
        <div class="dino" style="background: url('https://i.imgur.com/6cTgC6B.png') repeat-x;"></div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>
<?php }
?>

