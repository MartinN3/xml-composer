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
            <h1>Správa URL importů ze systému Pohoda</h1>
        </header>
        <main>
            <article>
                <header>
                    <h2>Poslední importy do systému</h2>
                </header>
                <section>
                    <h3>Data z databáze jsou následující:</h3>
                    <ul>
                        <li></li>
                    </ul>
                </section>
            </article>
            <hr>
            <article>
                <header>
                    <h2>Naimportovat nová data z Pohody</h2>
                    <p>Používá <i>&lsquo;Standartní export XML POHODA&rsquo;</i> formát výstupu ze systému Pohoda</p>
                </header>
                <section>
                    <form action="/import" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="systemName" name="systemName" value="pohoda">
                        <input type="hidden" id="uploadType" name="uploadType" value="url">
                        <div>
                            <label for="url">Url</label>
                            <input type="text" id="url" name="url">
                        </div>
                        <div>
                            <button type="submit">Upload</button>
                        </div>
                    </form>
                </section>
            </article>
            <hr>
            <article>
                <header>
                    <h2>Chtěli jste importovat soubor místo URL adresy?</h2>
                </header>
                <section>
                    <h3>Pohoda import pomocí <b>XML Souboru z disků PC</b></h3>
                    <a href="/pohoda/file">Pohoda Import pomocí XML Souboru z disků PC</a>
                </section>
            </article>
        </main>
    </body>
</html>
