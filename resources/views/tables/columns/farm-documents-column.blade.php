<div>


 @if($getRecord()->documents)

    @foreach ($getRecord()->documents as $document)


    <p class="text-xs px-0">
        <a href="{{$document->getFirstMediaUrl()}}" target="_blank" class="underline">{{$document->name}}</a>

    </p>

    @endforeach
    @endif

</div>
