const BtnSubmit  = document.querySelector(".submit");

BtnSubmit.addEventListener("click", AddNew);

function AddNew() {
    const newDiv = document.createElement("div");
    console.log("add");
    newDiv.classList.add("div-shadow");
    document.body.appendChild(newDiv);
}