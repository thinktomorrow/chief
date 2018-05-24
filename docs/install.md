## Required setup steps of your project after installment
- Extend in your project the Chief Exception handler.
- extend the Http kernel of chief
- publish all the config files:
    - translatable for providing the model translations
    - locale for allowing frontend translation
- Schema::defaultStringLength(191);
    