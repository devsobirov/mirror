// Initialize and handle phone input masks
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all phone input masks
    const phoneInputs = document.querySelectorAll('input[data-mask="(00) 000-00-00"]');
    
    phoneInputs.forEach(input => {
        // Get the IMask instance if it exists
        const maskInstance = IMask(input, {
            mask: '(00) 000-00-00',
            lazy: false
        });
        
        // Store the mask instance on the input element
        input._maskInstance = maskInstance;
        
        // Add a MutationObserver to watch for value changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    // Update mask when value changes
                    maskInstance.updateValue();
                }
            });
        });
        
        // Configure the observer to watch for value attribute changes
        observer.observe(input, {
            attributes: true,
            attributeFilter: ['value']
        });

        // Add event listener for programmatic value changes
        const originalSetAttribute = input.setAttribute;
        input.setAttribute = function(name, value) {
            originalSetAttribute.call(this, name, value);
            if (name === 'value' && this._maskInstance) {
                this._maskInstance.value = value;
                this._maskInstance.updateValue();
            }
        };

        // Monitor value property changes
        Object.defineProperty(input, 'value', {
            get: function() {
                return this.getAttribute('value') || '';
            },
            set: function(value) {
                this.setAttribute('value', value);
                if (this._maskInstance) {
                    this._maskInstance.value = value;
                    this._maskInstance.updateValue();
                }
            }
        });
    });
});