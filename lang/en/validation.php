<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ":attribute maydoni qabul qilinishi kerak.",
    'accepted_if' => ":other :value bo'lganda :attribute maydoni qabul qilinishi kerak.",
    'active_url' => ":attribute maydoni yaroqli URL bo'lishi kerak.",
    'after' => ":attribute maydoni :date sanadan keyingi sana bo'lishi kerak.",
    'after_or_equal' => ":attribute maydoni :date sanadan keyingi yoki unga teng sana bo'lishi kerak.",
    'alpha' => ":attribute maydoni faqat harflardan iborat bo'lishi kerak.",
    'alpha_dash' => ":attribute maydoni faqat harflar, raqamlar, chiziqcha va pastki chiziqdan iborat bo'lishi kerak.",
    'alpha_num' => ":attribute maydoni faqat harflar va raqamlardan iborat bo'lishi kerak.",
    'array' => ":attribute maydoni massiv bo'lishi kerak.",
    'ascii' => ":attribute maydoni faqat bir baytli alfanumerik belgilar va ramzlarni o'z ichiga olishi kerak.",
    'before' => ":attribute maydoni :date sanadan oldingi sana bo'lishi kerak.",
    'before_or_equal' => ":attribute maydoni :date sanadan oldingi yoki unga teng sana bo'lishi kerak.",
    'between' => [
        'array' => ":attribute maydoni :min va :max elementlar orasida bo'lishi kerak.",
        'file' => ":attribute maydoni :min va :max kilobayt orasida bo'lishi kerak.",
        'numeric' => ":attribute maydoni :min va :max orasida bo'lishi kerak.",
        'string' => ":attribute maydoni :min va :max belgilar orasida bo'lishi kerak.",
    ],
    'boolean' => ":attribute maydoni true yoki false bo'lishi kerak.",
    'can' => ":attribute maydonida ruxsat etilmagan qiymat mavjud.",
    'confirmed' => ":attribute tasdiqlash mos kelmadi.",
    'contains' => ":attribute maydonida kerakli qiymat yetishmayapti.",
    'current_password' => "Parol noto‘g‘ri.",
    'date' => ":attribute maydoni yaroqli sana bo‘lishi kerak.",
    'date_equals' => ":attribute maydoni :date sanasiga teng bo‘lishi kerak.",
    'date_format' => ":attribute maydoni :format formatiga mos kelishi kerak.",
    'decimal' => ":attribute maydoni :decimal kasr son bo‘lishi kerak.",
    'declined' => ":attribute maydoni rad etilishi kerak.",
    'declined_if' => ":other :value bo‘lganda :attribute maydoni rad etilishi kerak.",
    'different' => ":attribute va :other maydonlari har xil bo‘lishi kerak.",
    'digits' => ":attribute maydoni :digits raqam bo‘lishi kerak.",
    'digits_between' => ":attribute maydoni :min dan :max gacha bo‘lgan raqamlar orasida bo‘lishi kerak.",
    'dimensions' => ":attribute maydonida yaroqsiz rasm o‘lchamlari mavjud.",
    'distinct' => ":attribute maydonida dublikat qiymat mavjud.",
    'doesnt_end_with' => ":attribute maydoni quyidagi qiymatlar bilan tugamasligi kerak: :values.",
    'doesnt_start_with' => ":attribute maydoni quyidagi qiymatlar bilan boshlanmasligi kerak: :values.",
    'email' => ":attribute maydoni yaroqli email manzil bo‘lishi kerak.",
    'ends_with' => ":attribute maydoni quyidagi qiymatlar bilan tugashi kerak: :values.",
    'enum' => "Tanlangan :attribute yaroqsiz.",
    'exists' => "Tanlangan :attribute yaroqsiz.",
    'extensions' => ":attribute maydoni quyidagi kengaytmalarga ega bo‘lishi kerak: :values.",
    'file' => ":attribute maydoni fayl bo‘lishi kerak.",
    'filled' => ":attribute maydoni qiymatga ega bo‘lishi kerak.",
    'gt' => [
        'array' => 'The :attribute field must have more than :value items.',
        'file' => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than :value.',
        'string' => 'The :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute field must have :value items or more.',
        'file' => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than or equal to :value.',
        'string' => 'The :attribute field must be greater than or equal to :value characters.',
    ],
    'hex_color' => ":attribute maydoni haqiqiy oltingugurt rang kodi bo'lishi kerak.",
    'image' => ":attribute maydoni rasm bo'lishi kerak.",
    'in' => "Tanlangan :attribute noto'g'ri.",
    'in_array' => ":attribute maydoni :other ichida mavjud bo'lishi kerak.",
    'integer' => ":attribute maydoni butun son bo'lishi kerak.",
    'ip' => ":attribute maydoni haqiqiy IP manzil bo'lishi kerak.",
    'ipv4' => ":attribute maydoni haqiqiy IPv4 manzil bo'lishi kerak.",
    'ipv6' => ":attribute maydoni haqiqiy IPv6 manzil bo'lishi kerak.",
    'json' => ":attribute maydoni haqiqiy JSON satr bo'lishi kerak.",
    'list' => ":attribute maydoni ro'yxat bo'lishi kerak.",
    'lowercase' => ":attribute maydoni kichik harflarda bo'lishi kerak.",
    'lt' => [
        'array' => ":attribute maydoni :value ta elementdan kam bo'lishi kerak.",
        'file' => ":attribute maydoni :value kilobaytdan kam bo'lishi kerak.",
        'numeric' => ":attribute maydoni :value dan kam bo'lishi kerak.",
        'string' => ":attribute maydoni :value ta belgidan kam bo'lishi kerak.",
    ],
    'lte' => [
        'array' => ":attribute maydoni :value ta elementdan ortiq bo'lmasligi kerak.",
        'file' => ":attribute maydoni :value kilobaytdan kam yoki teng bo'lishi kerak.",
        'numeric' => ":attribute maydoni :value dan kam yoki teng bo'lishi kerak.",
        'string' => ":attribute maydoni :value ta belgidan kam yoki teng bo'lishi kerak.",
    ],
    'mac_address' => ":attribute maydoni haqiqiy MAC manzil bo'lishi kerak.",
    'max' => [
        'array' => ":attribute maydoni :max ta elementdan ortiq bo'lmasligi kerak.",
        'file' => ":attribute maydoni :max kilobaytdan katta bo'lmasligi kerak.",
        'numeric' => ":attribute maydoni :max dan katta bo'lmasligi kerak.",
        'string' => ":attribute maydoni :max ta belgidan ortiq bo'lmasligi kerak.",
    ],
    'max_digits' => ":attribute maydoni :max ta raqamdan ortiq bo'lmasligi kerak.",
    'mimes' => ":attribute maydoni quyidagi turdagi fayl bo'lishi kerak: :values.",
    'mimetypes' => ":attribute maydoni quyidagi turdagi fayl bo'lishi kerak: :values.",
    'min' => [
        'array' => ":attribute maydoni kamida :min ta elementdan iborat bo'lishi kerak.",
        'file' => ":attribute maydoni hajmi kamida :min kilobayt bo'lishi kerak.",
        'numeric' => ":attribute maydoni kamida :min bo'lishi kerak.",
        'string' => ":attribute maydoni kamida :min ta belgidan iborat bo'lishi kerak.",
    ],
    'min_digits' => ":attribute maydoni kamida :min ta raqamdan iborat bo'lishi kerak.",
    'missing' => ":attribute maydoni mavjud bo'lmasligi kerak.",
    'missing_if' => ":other :value bo'lganda :attribute maydoni mavjud bo'lmasligi kerak.",
    'missing_unless' => ":other :value bo'lmasa, :attribute maydoni mavjud bo'lmasligi kerak.",
    'missing_with' => ":values mavjud bo'lganda :attribute maydoni mavjud bo'lmasligi kerak.",
    'missing_with_all' => ":values mavjud bo'lganda :attribute maydoni mavjud bo'lmasligi kerak.",
    'multiple_of' => ":attribute maydoni :value ning ko'paytmasi bo'lishi kerak.",
    'not_in' => "Tanlangan :attribute noto'g'ri.",
    'not_regex' => ":attribute maydoni formati noto'g'ri.",
    'numeric' => ":attribute maydoni raqam bo'lishi kerak.",
    'password' => [
        'letters' => ":attribute kamida bitta harfni o'z ichiga olishi kerak.",
        'mixed' => ":attribute kamida bitta katta va bitta kichik harfni o'z ichiga olishi kerak.",
        'numbers' => ":attribute kamida bitta raqamni o'z ichiga olishi kerak.",
        'symbols' => ":attribute kamida bitta maxsus belgi o'z ichiga olishi kerak.",
        'uncompromised' => "Berilgan :attribute ma'lumotlar oqishida paydo bo'lgan. Iltimos, boshqa :attribute tanlang.",
    ],
    'present' => ":attribute maydoni mavjud bo'lishi kerak.",
    'present_if' => ":other :value bo'lganda :attribute maydoni mavjud bo'lishi kerak.",
    'present_unless' => ":other :value bo'lmasa, :attribute maydoni mavjud bo'lishi kerak.",
    'present_with' => ":values mavjud bo'lganda :attribute maydoni ham mavjud bo'lishi kerak.",
    'present_with_all' => ":values mavjud bo'lganda :attribute maydoni ham mavjud bo'lishi kerak.",
    'prohibited' => ":attribute maydoni taqiqlangan.",
    'prohibited_if' => ":other :value bo'lganda :attribute maydoni taqiqlanadi.",
    'prohibited_unless' => ":other :values ichida bo'lmasa, :attribute maydoni taqiqlanadi.",
    'prohibits' => ":attribute maydoni :other maydonining mavjud bo'lishiga to'sqinlik qiladi.",
    'regex' => ":attribute maydonining formati noto'g'ri.",
    'required' => ":attribute maydoni talab qilinadi.",
    'required_array_keys' => ":attribute maydoni quyidagi kalitlarni o'z ichiga olishi kerak: :values.",
    'required_if' => ":other :value bo'lganda :attribute maydoni talab qilinadi.",
    'required_if_accepted' => ":other qabul qilingan bo'lsa, :attribute maydoni talab qilinadi.",
    'required_if_declined' => ":other rad etilgan bo'lsa, :attribute maydoni talab qilinadi.",
    'required_unless' => ":other :values ichida bo'lmasa, :attribute maydoni talab qilinadi.",
    'required_with' => ":values mavjud bo'lganda :attribute maydoni talab qilinadi.",
    'required_with_all' => ":values mavjud bo'lganda :attribute maydoni talab qilinadi.",
    'required_without' => ":values mavjud bo'lmaganda :attribute maydoni talab qilinadi.",
    'required_without_all' => ":values dan birortasi mavjud bo'lmaganda :attribute maydoni talab qilinadi.",
    'same' => ":attribute maydoni :other bilan mos kelishi kerak.",
    'size' => [
        'array' => ":attribute maydoni :size ta elementdan iborat bo'lishi kerak.",
        'file' => ":attribute maydoni hajmi :size kilobayt bo'lishi kerak.",
        'numeric' => ":attribute maydoni :size bo'lishi kerak.",
        'string' => ":attribute maydoni :size ta belgidan iborat bo'lishi kerak.",
    ],
    'starts_with' => ":attribute maydoni quyidagilardan biri bilan boshlanishi kerak: :values.",
    'string' => ":attribute maydoni matn bo'lishi kerak.",
    'timezone' => ":attribute maydoni to'g'ri vaqt zonasi bo'lishi kerak.",
    'unique' => ":attribute allaqachon band qilingan.",
    'uploaded' => ":attribute yuklash muvaffaqiyatsiz tugadi.",
    'uppercase' => ":attribute maydoni faqat katta harflardan iborat bo'lishi kerak.",
    'url' => ":attribute maydoni to'g'ri URL bo'lishi kerak.",
    'ulid' => ":attribute maydoni to'g'ri ULID bo'lishi kerak.",
    'uuid' => ":attribute maydoni to'g'ri UUID bo'lishi kerak.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
