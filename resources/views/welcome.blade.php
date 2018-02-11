<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 
                /*Safari for OS X and iOS (San Francisco)*/
                  -apple-system,
                  /*Chrome >= 56 for OS X (San Francisco), Windows, Linux and Android*/
                  system-ui,
                  /*Chrome < 56 for OS X (San Francisco)*/
                  BlinkMacSystemFont,
                  /*Windows*/
                  "Segoe UI",
                  /*Android*/
                  "Roboto",
                  /*Basic web fallback*/
                  "Helvetica Neue", Arial, sans-serif;
                font-weight: 100;
                margin: 0;
            }

            body > header {
                padding: 0 1em;
            }

            article {
                padding: 0 1em;
                margin: 2em auto;
            }
            .content {
                padding: 0 20px;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>XML Composer</h1>
            <p>Vítejte na hlavní stránce aplikace XML composer, která vznikla </p>
        </header>
        <main>
            <article>
                <header>
                    <h2>Pohoda Import</h2>
                    <p>Používá <i>&lsquo;Standartní export XML POHODA&rsquo;</i> formát výstupu ze systému Pohoda</p>
                </header>

                <section>
                    <h3>Pohoda import pomocí <b>XML Souboru z disků PC</b></h3>
                    <a href="/pohoda/file">Pohoda Import pomocí XML Souboru z disků PC</a>
                </section>
                <section>
                    <h3>Pohoda import pomocí <b>URL</b>, tedy ze vzdáleného PC</h3>
                    <a href="/pohoda/url">Pohoda Import pomocí URL, tedy ze vzdáleného PC</a>
                </section>
            </article>
            <hr>
            <article>
                <header>
                    <h2>Shoptet Import</h2>
                    <p>Používá <i>&lsquo;systémový: <b>Kompletní export</b> - XML&rsquo;</i> formát výstupu ze systému Shoptet</p>
                </header>
                <section>
                    <h3>Shoptet import pomocí <b>XML Souboru z disků PC</b></h3>
                    <a href="/shoptet/file">Shoptet Import pomocí XML Souboru z disků PC</a>
                </section>
                <section>
                    <h3>Shoptet Import pomocí <b>URL</b> z jejich serverů</h3>
                    <a href="/shoptet/url">Shoptet import pomocí URL, tedy z jejich serverů</a>
                </section>
            </article>
        </main>
    </body>
</html>
