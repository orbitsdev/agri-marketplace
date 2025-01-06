@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-eucalyptus-500 focus:ring-eucalyptus-500 rounded-md shadow-sm']) !!}>
