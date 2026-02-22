document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const collegeId = document.getElementById('college_id').value.trim();
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        if (collegeId.length < 6 || collegeId.length > 20) {
            alert('College ID must be 6-20 alphanumeric characters (e.g., VTU2025001)');
            e.preventDefault();
            return;
        }
        
        if (!/^[A-Za-z0-9]+$/.test(collegeId)) {
            alert('College ID must contain only letters and numbers');
            e.preventDefault();
            return;
        }
        
        if (name.length < 2) {
            alert('Name must be at least 2 characters');
            e.preventDefault();
            return;
        }
        
        if (phone.length !== 10 || !/^\d{10}$/.test(phone)) {
            alert('Phone must be exactly 10 digits');
            e.preventDefault();
            return;
        }
        
        if (confirm('Register student with College ID: ' + collegeId + '?')) {
        } else {
            e.preventDefault();
        }
    });
});
