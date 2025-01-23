document.querySelector("#loginButton").addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Capture form data
    var domain = document.querySelector("#domain").value;
    var username = document.querySelector("#username").value;
    var password = document.querySelector("#password").value;

    // Send data to login.php using fetch

    if (domain === "teacher") {
        fetch("login/teacher_login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                domain: domain,
                username: username,
                password: password
            })
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function (response) {
                console.log("Server response:", response); // Log the server response
                if (response.success) {
                    window.location.href = "teacher_dashboard.php";
                } 
                else {
                    alert(response.msg || "Login failed. Please try again.");
                }
            })
    }
    else if (domain === "student") {
        fetch("login/student_login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                domain: domain,
                username: username,
                password: password
            })
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function (response) {
                console.log("Server response:", response); // Log the server response
                if (response.success) {
                    window.location.href = "student_dashboard.php";
                  
                } 
                else {
                    alert(response.msg || "Login failed. Please try again.");
                }
            })

    }
    else if (domain === "admin") {
        fetch("login/admin_login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                domain: domain,
                username: username,
                password: password
            })
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(function (response) {
                console.log("Server response:", response); // Log the server response
                if (response.success) {
                    window.location.href = "admin_dashboard.php";
                  
                } 
                else {
                    alert(response.msg || "Login failed. Please try again.");
                }
            })

    }


    
});

