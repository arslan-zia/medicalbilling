// Start: Validate phone number input for add_user.php, edit_user.php, add_lead.php, edit_lead.php
function validatePhoneNumber(e) {
    // Prevent non-digit input except backspace, delete, arrow keys
    if (!/^\d$/.test(e.key) && 
        !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
        e.preventDefault();
        return false;
    }
    
    // Get the current value and remove any spaces
    let currentValue = e.target.value.replace(/\s/g, '');
    
    // Handle backspace/delete
    if (['Backspace', 'Delete'].includes(e.key)) {
        return true;
    }
    
    // Prevent input if it would exceed 12 digits
    if (currentValue.length >= 12) {
        e.preventDefault();
        return false;
    }
    
    // Add space after 3 digits
    if (currentValue.length === 3) {
        e.target.value = currentValue + ' ' + e.key;
        e.preventDefault();
        return false;
    }
    
    // Validate minimum length
    if ((currentValue + e.key).length < 7) {
        e.target.setCustomValidity('Phone number must be between 7-12 digits');
    } else {
        e.target.setCustomValidity('');
    }
}

// Format existing value on load
window.addEventListener('load', function() {
    // Format all phone number inputs on the page
    document.querySelectorAll('input[type="tel"]').forEach(input => {
        if (input.value.length >= 3) {
            const digits = input.value.replace(/\s/g, '');
            input.value = digits.substring(0,3) + ' ' + digits.substring(3);
        }
    });
});
// End: Validate phone number input for add_user.php, edit_user.php, add_lead.php, edit_lead.php

// Auto format cnic input on Edit User Page
$(document).ready(function() {
    var cnicInput = $('input[name="cnic"]').val();
    if (cnicInput) {
        // Clean the value of any non-digits
        var cleanValue = cnicInput.replace(/[^0-9]/g, '');
        // Add hyphens at correct positions
        if (cleanValue.length > 5) {
            cleanValue = cleanValue.slice(0,5) + '-' + cleanValue.slice(5);
        }
        if (cleanValue.length > 13) {
            cleanValue = cleanValue.slice(0,13) + '-' + cleanValue.slice(13);
        }
        // Update input value
        $('input[name="cnic"]').val(cleanValue);
    }
});
// End: Auto format cnic input on Edit User Page

// Auto format ssn input on Edit Lead Page
function formatSSN(event) {
    // Allow only numbers, backspace, delete, tab, arrows
    if (!/^\d$/.test(event.key) && 
        !['Backspace','Delete','Tab','ArrowLeft','ArrowRight'].includes(event.key)) {
        event.preventDefault();
        return false;
    }

    let input = event.target;
    let value = input.value.replace(/\D/g, '');
    
    // Add dashes automatically while typing
    if (value.length > 3 && value.length <= 5) {
        value = value.slice(0,3) + '-' + value.slice(3);
    }
    if (value.length > 5) {
        value = value.slice(0,3) + '-' + value.slice(3,5) + '-' + value.slice(5);
    }
    
    // Validate SSN rules while typing
    let ssn = value.replace(/-/g, '');
    if (ssn.length === 9) {
        if (/^(\d)\1+$/.test(ssn)) {
            input.setCustomValidity('SSN cannot contain all same digits');
        } else if (['000','666','900','901','902','903','904','905','906','907','908','909'].includes(ssn.slice(0,3))) {
            input.setCustomValidity('Invalid SSN prefix');
        } else {
            input.setCustomValidity('');
        }
    }

    // Update input value
    input.value = value.slice(0,11);
}
// End: Auto format ssn input on Edit Lead Page

