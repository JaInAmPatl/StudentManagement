document.getElementById("addButton").addEventListener("click", function (event) {
    event.preventDefault(); // Prevent default form submission

    var id =  document.querySelector("#username").value;
    var name =  document.querySelector("#Studentname").value;
    var email =  document.querySelector("#email").value;
    var password =  document.querySelector("#password").value;
    var confirmPassword =  document.querySelector("#confirmPassword").value;

    var successMessage= document.querySelector("#msg");

    if (password === confirmPassword && name != 'undefined') {
        console.log(name);
        fetch ("logic/addStudents.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                id: id,
                name: name,
                email: email,
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
                    successMessage.textContent = `Student added successfully to the database`;
                    

                } 
                else {
                    alert(response.msg || " Failed. Please try again.");
                }
            })
        
    }
    else {
        alert("Passwords do not match. Please try again."); 

    }
    
   




});