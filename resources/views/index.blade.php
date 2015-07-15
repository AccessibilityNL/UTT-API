<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bookmarklet Launcher</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<header>
    <div class="pull-left">
        Bookmarklet API explorer
    </div>
    <div class="pull-right">
        API Version:
        <div class="btn-group dropdown">
            <button type="button"
                    id="version"
                    class="btn btn-default dropdown-toggle dropdown-version-label"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <span class="title">v1.0</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-version">
                <li><span class="version-v1">v1.0</span></li>
                <li><span class="version-v1-1">v1.1</span></li>
                <li><span class="version-v2">v2.0</span></li>
            </ul>
        </div>
    </div>
</header>

<article>

    <div class="row">

        <div class="col-md-4">
            <!-- Single button -->
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        id="method"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <span class="title">GET</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><span class="method-get">GET</span></li>
                    <li><span class="method-post">POST</span></li>
                </ul>
            </div>

            <!-- Single button -->
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        id="entity"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <span class="title">Choose entity</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><span class="entity-assertions">Assertion</span></li>
                    <li><span class="entity-assertors">Assertor</span></li>
                    <li><span class="entity-webpages">Webpage</span></li>
                    <li><span class="entity-evaluations">Evaluation</span></li>
                </ul>
            </div>
        </div>
        <div class="form-group col-md-6">
            <input id="inputUrl" type="text" class="form-control" placeholder="/v1">
        </div>

        <div class="col-md-2">
            <button type="button" id="doRequest" class="btn btn-default btn-submit">Submit</button>
        </div>
    </div>

    <div class="row bigger-panel">

        <div class="col-md-4">
            <select id="presets" multiple class="form-control"></select>
        </div>

        <div class="col-md-8">

            <textarea id="output" class="form-control" rows="3"></textarea>

        </div>
    </div>


</article>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script type="application/javascript">

    $(function () {

        var parameters = {
            'base': '/',
            'method': 'GET',
            'entity': '',
            'version': 'v1',
            'url': ''
        };

        var dropdowns = {
            "version": [
                ".version-v1",
                ".version-v1-1",
                ".version-v2"
            ],
            "method": [
                ".method-get",
                ".method-post"
            ],
            "entity": [
                ".entity-assertions",
                ".entity-assertors",
                ".entity-webpages",
                ".entity-evaluations"
            ]
        };

        var presets = {
            "List assertions": {
                'method': 'GET',
                'entity': 'Assertion'
            },
            "List webpages": {
                'method': 'GET',
                'entity': 'Webpage'
            },
            "Assertion context": {
                'method': 'GET',
                'entity': 'Assertion'
            }
        };

        var setParametersFromPreset = function (title) {

            var preset = presets[title];

            parameters['method'] = preset['method'];
            parameters['entity'] = preset['entity'];

            $("#method").find(".title")[0].innerText = preset['method'];
            $("#entity").find(".title")[0].innerText = preset['entity'];

            setURLToInput();
        };

        var setURLToInput = function () {

            var url = '';
            if(parameters['url'])
                url = parameters['url'];

            $("#inputUrl").val(
                    parameters['base'] +
                    parameters['version'] +
                    '/' + parameters['entity'].toLowerCase() + 's',
                    url
            );
        };

        var setDropdownText = function (key, value) {
            $(value).on("click", function (event) {
                var v = event.currentTarget.innerText;
                $("#" + key + " .title")[0].innerText = v;
                parameters[key] = v;
                setURLToInput(parameters['url']);
            });

        };

        for (var elemKey in dropdowns) {
            var values = dropdowns[elemKey];
            for (var valueKey in values) {
                var value = values[valueKey];
                setDropdownText(elemKey, value);
            }
        }

        var pElem = $("#presets");
        for (var key in presets) {
            pElem.append("<option value=''>" + key + "</option>");
        }

        pElem.find(">option").click(function (event) {
            setParametersFromPreset(event.currentTarget.text)
        });

        $("#doRequest").click(function () {
            console.log(parameters);

            $.ajax({
                "method": parameters["method"],
                "url": $("#inputUrl").val()
            }).done(function (data, textStatus, jqXHR) {
                console.log(data);
                $("#output").empty().append(data);
            }).fail(function (jqXHR) {
                console.log(jqXHR.status + " - " + jqXHR.statusText);
            });

        });

    });

</script>
</body>
</html>
