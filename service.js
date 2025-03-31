const BtnSubmit = document.querySelector(".submit");
const flexContainer = document.getElementById("main"); // Assuming this is the correct container for services

BtnSubmit.addEventListener("click", AddNew);

function AddNew(event) {
    event.preventDefault(); // Prevent the form from submitting

    // Get form values
    const serviceTitle = document.getElementById("service-title").value;
    const serviceCategory = document.getElementById("service-category").value;
    const servicePrice = document.getElementById("service-price").value;
    const serviceDescription = document.getElementById("service-description").value;
    const serviceImage = document.getElementById("service-image").files[0];

    // Basic validation
    if (!serviceTitle || !serviceCategory || !servicePrice || !serviceDescription || !serviceImage) {
        alert("Please fill out all fields and upload an image.");
        return;
    }

    // Create a new div for the service
    const newDiv = document.createElement("div");
    newDiv.classList.add("service");

    // Create and append elements to the new div
    const img = document.createElement("img");
    img.src = URL.createObjectURL(serviceImage); // Create a URL for the uploaded image
    img.alt = serviceTitle;

    const title = document.createElement("h4");
    title.textContent = serviceTitle;

    const price = document.createElement("p");
    price.textContent = `Starting at $${servicePrice}`;

    const description = document.createElement("p");
    description.textContent = serviceDescription;

    const editButton = document.createElement("button");
    editButton.textContent = "Edit";
    // Add functionality for edit button if needed

    const deleteButton = document.createElement("button");
    deleteButton.textContent = "Delete";
    deleteButton.addEventListener("click", () => {
        flexContainer.removeChild(newDiv); // Remove the service div on delete
    });

    // Append all elements to the new div
    newDiv.appendChild(img);
    newDiv.appendChild(title);
    newDiv.appendChild(price);
    newDiv.appendChild(description);
    newDiv.appendChild(editButton);
    newDiv.appendChild(deleteButton);

    // Append the new div to the flex container
    flexContainer.appendChild(newDiv);

    // Clear the form fields after submission
    document.getElementById("service-form").reset();
}