<div>
    @livewire('buyer-header')
    {{$slot}}
    
    @if(isset($footer))
        {{ $footer }}
    @endif
</div>
