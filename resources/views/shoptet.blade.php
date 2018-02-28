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
            <h1>Shoptet</h1>
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
                    <h2>Shoptet import pomocí <b>URL exportu</b></h2>
                </header>
                <section>
                    <p>Používá <i>&lsquo;Standartní export XML SHOPTET&rsquo;</i> formát výstupu ze systému Shoptet</p>
                    <a href="/shoptet/url">Shoptet Import pomocí XML URL exportu</a>
                </section>
            </article>
            <hr>
            <article>
                <header>
                    <h2>Shoptet import pomocí <b>XML Souboru z disků PC</b></h2>
                </header>
                <section>
                    <p></p>
                    <a href="/shoptet/file">Shoptet Import pomocí XML Souboru z disků PC</a>
                </section>
            </article>
            <hr>
            <article>
                <header>
                    <h2>Provést zálohu obrázků z databáze Shoptet</h2>
                </header>
                <section>
                    <form action="/backup" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="systemName" name="systemName" value="shoptet">
                        <div>
                            <button type="submit">Zálohovat</button>
                        </div>
                    </form>
                </section>
            </article>
            <hr>
            <article>
                <form action="/validate/show" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="systemName" name="systemName" value="shoptet">
                    <p>
                        <select name="validationRequest" id="validationRequest">
                            <option value="validateLength">Validovat Short Description</option>
                        </select>
                    </p>
                    <div>
                        <button type="submit">Validovat</button>
                    </div>
                </form>
            </article>
            <hr>
        </main>
    </body>
</html>
