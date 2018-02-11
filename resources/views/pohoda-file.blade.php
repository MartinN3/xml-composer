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
            <h1>Pohoda import pomocí XML Souboru z disků PC</h1>
            <p></p>
        </header>
        <main>
            <article>
                <header>
                    <h2>Místo pro nahrání XML souboru</h2>
                    <p>Používá <i>&lsquo;Standartní export XML POHODA&rsquo;</i> formát výstupu ze systému Pohoda</p>
                </header>

                <section>
                    <form action="/import" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="systemName" name="systemName" value="pohoda">
                        <input type="hidden" id="uploadType" name="uploadType" value="file">
                        <div>
                            <label for="xmlFile">Upload XML</label>
                            <input type="file" id="xmlFile" name="xml">
                        </div>
                        <div>
                            <button type="submit">Upload</button>
                        </div>
                    </form>
                </section>
            </article>
            <hr>
        </main>
    </body>
</html>
