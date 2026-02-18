{{--
    Age Gate — AIVidCatalog18
    Displayed before any content. Visitor must confirm 18+ age.
    Strictly fictional AI-generated content — no illegal/prohibited material.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('age_verification_title') }} — {{ __('site_name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-4">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">⚠️</div>
                <h1 class="text-2xl font-bold text-white mb-2">{{ __('age_verification_title') }}</h1>
                <p class="text-gray-400 text-sm leading-relaxed">{{ __('age_verification_description') }}</p>
            </div>

            {{-- Error messages --}}
            @if($errors->any())
                <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Age verification form --}}
            <form action="{{ route('age-gate.verify') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="intended_url" value="{{ session('intended_url', '/') }}">

                {{-- Date of birth (optional enhancement) --}}
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-300 mb-2">
                        {{ __('birth_date_label') }}
                    </label>
                    <input type="date" 
                           name="birth_date" 
                           id="birth_date"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           max="{{ now()->subYears(18)->format('Y-m-d') }}">
                </div>

                {{-- Confirmation checkbox --}}
                <div class="flex items-start space-x-3">
                    <input type="checkbox" 
                           name="confirm_age" 
                           id="confirm_age"
                           value="1"
                           class="mt-1 w-5 h-5 rounded bg-gray-800 border-gray-600 text-blue-600 focus:ring-blue-500"
                           required>
                    <label for="confirm_age" class="text-sm text-gray-300 leading-relaxed cursor-pointer">
                        {{ __('age_verification_confirm') }}
                    </label>
                </div>

                {{-- Legal notice --}}
                <p class="text-xs text-gray-500 leading-relaxed">
                    {{ __('age_verification_legal') }}
                </p>

                {{-- Buttons --}}
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        {{ __('age_verification_enter') }}
                    </button>
                    <a href="https://google.com" 
                       class="flex-1 bg-gray-800 hover:bg-gray-700 text-gray-300 font-semibold py-3 px-6 rounded-lg transition duration-200 text-center">
                        {{ __('age_verification_leave') }}
                    </a>
                </div>
            </form>
        </div>

        {{-- Bottom disclaimer --}}
        <p class="text-center text-xs text-gray-600 mt-6">
            {{ __('site_disclaimer') }}
        </p>
    </div>

</body>
</html>
