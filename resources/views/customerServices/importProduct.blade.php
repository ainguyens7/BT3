
@if (is_null($result))
    <div><p>Click enter to import product<p></p></div>
@elseif (!$result)
    <div><p>Import product was error<p></p></div>
@else
    <div><p>Import product add to worker successful<p></p></div>
@endif

<div class="url-import">
    <form method="post" action="{{ route('cs.importProductHandle') }}" name="importProduct">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <span class="url-import-block">
            <span>
                <input type="submit" value="ENTER">
            </span>
        </span>
    </form>
</div>
