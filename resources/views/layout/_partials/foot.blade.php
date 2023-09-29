<script src="{{ chief_cached_asset('js/main.js') }}"></script>

<!-- place to add custom vue components, right before the global Vue instance is created -->
@stack('custom-components')

@livewireScript

<script src="{{ chief_cached_asset('js/main.js') }}"></script>

@stack('custom-scripts')

@stack('custom-scripts-after-vue')

</body>
</html>
