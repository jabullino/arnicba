<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center justify-center bg-sky-700 text-white rounded-md mx-0 h-12 text-center w-full'
]) }}>
    {{ $slot }}
</button>

