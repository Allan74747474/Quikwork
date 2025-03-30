const BtnSubmit  = document.querySelector(".submit");

BtnSubmit.addEventListener("click", AddNew);

function AddNew() {
    const newDiv = document.createElement("div");
    console.log("add");
    document.body.appendChild(newDiv);
}