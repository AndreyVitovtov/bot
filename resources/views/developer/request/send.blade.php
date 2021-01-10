@extends("developer.template")

@section("title")
    Request
@endsection

@section("h3")
    <h3>Send request</h3>
@endsection

@section("main")
    <style>
        textarea {
            width: 100%;
            height: 100px;
            resize: none;
            border: solid 1px #ddd;
        }

        iframe {
            height: 390px;
        }
    </style>

    <form action="{{ route('get-response') }}" method="POST">
        @csrf
        <div>
            <input type="checkbox" name="messenger" value="viber" id="viber"
                @if((isset($messenger) && $messenger == 'viber'))
                    checked
                @endif
            >
            <label for="viber" class="cursor-pointer">Viber</label>
        </div>
        <br>
        <div>
            <input type="radio" name="method" value="post" id="post"
            @if((isset($method) && $method == 'post') || !isset($method))
                checked
            @endif
            class="method">
            <label for="post" class="cursor-pointer">POST</label>
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="method" value="get" id="get"
            @if(isset($method) && $method == 'get')
               checked
            @endif
            class="method">
            <label for="get" class="cursor-pointer">GET</label>
        </div>
        <br>
        <div>
            <label for="url">Url</label>
            <input type="text" name="url" value="{{ isset($url) ? $url : route('bot-request-handler') }}" id="url">
        </div>
        <br>
        <div>
            <label for="request">Request</label>
            <textarea name="data" id="request">{{ $data }}</textarea>
        </div>
        <br>
        <button class="button">@lang('pages.send')</button>
    </form>
    @if(isset($response) && $response != null)
        <br>
        <br>
        <label>Response</label>
        <br>
        @if(substr($response, 0, 1) == '{')
            <div id="json-renderer" class="json-body"></div>
            <script>
                let json = '{!! $response !!}';
                $('#json-renderer').jsonBrowse(JSON.parse(json), {withQuotes: true});
            </script>
        @else
            <iframe src="{{ url('html/response.html') }}" width="100%"></iframe>
        @endif
    @endif

    <script>
        let method = $('input[name=method]:checked').val();

        if(method == 'get') {
            $('textarea').prop('disabled', true);
        }
        $('body').on('change', '.method', function() {
            method = $('input[name=method]:checked').val();

            if(method == 'get') {
                $('textarea').prop('disabled', true);
            }
            else {
                $('textarea').prop('disabled', false);
            }
        });
    </script>
@endsection
