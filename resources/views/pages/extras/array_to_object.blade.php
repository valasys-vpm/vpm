<html>
    <body>
        <form action="{{ route('array_to_object') }}" method="POST">
            @csrf
            <label for="string_to_covert">Enter string to convert</label>
            <br>
            <textarea name="string_to_covert" id="string_to_covert" cols="100" rows="10"></textarea>
            <br><br>
            <input type="submit" value="Convert" name="submit">
        </form>

        @if(isset($result) && !empty($result))
        <div style="margin-top: 20px;width: 80%;word-wrap: break-word;">
            {{ $result }}
        </div>
        @endif
    </body>
</html>
