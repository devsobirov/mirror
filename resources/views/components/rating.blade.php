@props(['rating'])

@if($rating)
    @php 
        $ratingColor = match (true) {
            $rating <= 1 => '#FF0000', // Red
            $rating <= 2 => '#FF7F00', // Orange
            $rating <= 3 => '#FFFF00', // Yellow
            $rating <= 4 => '#9ACD32', // YellowGreen
            $rating <= 5 => '#008000', // Green
            default => '#808080', // Gray
        };
    @endphp
    <span class="rating" style="color: {{ $ratingColor }}">
        {{$rating}}
        <x-svg.star></x-svg.star>
    </span>
@else
    <span class="rating">-</span>
@endif

