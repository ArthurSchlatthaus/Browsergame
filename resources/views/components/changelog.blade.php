<div class="d-flex flex-column">
    @if(Auth::user()->isAdmin())
        <h4>ADD Changelog</h4>
        <form name="form" action="{{route('saveChangelog')}}" method="post">
            @csrf
            <input type="text" name="changelogText" id="changelogText">
            <button type="submit" class="btn btn-dark">Save Changelog</button>
        </form>
    @endif
    @foreach(\App\Models\Changelog::orderBy('created_at', 'DESC')->get() as $changelog)
        <h4>{{date("h:i, d F y", strtotime($changelog->created_at))}}</h4>
        <ul>
            @foreach(explode('//',$changelog->text) as $text)
                <li>{{ $text }}</li>
            @endforeach
            @if(Auth::user()->isAdmin())
                <form name="form" action="{{route('editChangelog')}}" method="post">
                    @csrf
                    <input type="text" id="changelog_text" name="changelog_text" value="{{$changelog->text}}">
                    <input type="hidden" name="changelog_id" id="changelog_id" value="{{$changelog->id}}">
                    <button type="submit" class="btn btn-dark">Save Changelog</button>
                </form>
            @endif
        </ul>
    @endforeach
</div>
