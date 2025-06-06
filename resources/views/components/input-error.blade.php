{{-- Muestra errores de validación específicos en texto rojo debajo de cada campo --}}
@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
@enderror
