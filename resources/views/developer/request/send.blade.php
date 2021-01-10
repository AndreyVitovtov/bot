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
            <input type="radio" name="migrate_seed" value="none" class="migrate-seed" id="none" checked>
            <label for="none" class="cursor-pointer">None</label>

            <input type="radio" name="migrate_seed" value="migrate" class="migrate-seed" id="migrate">
            <label for="migrate" class="cursor-pointer">Migrate</label>

            <input type="radio" name="migrate_seed" value="seed" class="migrate-seed" id="seed">
            <label for="seed" class="cursor-pointer">Seed</label>
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
            <input type="hidden" name="old_url" value="{{ isset($url) ? $url : route('bot-request-handler') }}" id="old_url">
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
        $('body').on('change', '.migrate-seed', function() {
            let url = $('#old_url').val();
            $('#url').val(url);
            let radio = $(this).val();
            if(radio === 'none') {
                $('#post').prop('checked', true);
                $('textarea').prop('disabled', false);
                return;
            }
            let old_url = $('#old_url').val();
            $('#old_url').val(old_url);
            $('#get').prop('checked', true);
            $('textarea').prop('disabled', true);
            $('#url').val('{{ url('') }}/'+radio);
        });

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
