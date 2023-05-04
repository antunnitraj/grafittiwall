elementi = {
    prijava: document.getElementById("prijava"),
    prijavaresponse: document.getElementById("prijavaresponse"),
    odjava: document.getElementById("odjava"),
    komentiranje: document.getElementById("komentiranje"),
    komentarresponse: document.getElementById("komentarresponse"),
    korisnik: document.getElementById("korisnik"),
    odjavabutt: document.getElementById("odjavabutt"),
}

elementi.prijava.addEventListener("submit", function(e){
    e.preventDefault();
    const form = this;
    var data = new FormData(form);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'prijava.php', true);
    xhr.onload = function () {
        if(this.responseText){
            form.reset();
            elementi.prijava.style.display = "none";
            elementi.odjava.style.display = "block";
            elementi.komentiranje.style.display = "block";
            elementi.korisnik.innerText = this.responseText;
            elementi.prijavaresponse.innerText = "";
        } else {
            elementi.prijavaresponse.innerText = "Krivo korisničko ime ili lozinka!";
        }
    };
    xhr.send(data);
});

elementi.odjavabutt.addEventListener("click", function(e){
    e.preventDefault();
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'odjava.php');
    xhr.onload = function () {
        elementi.prijavaresponse.innerText = "";
        elementi.prijava.style.display = "block";
        elementi.odjava.style.display = "none";
        elementi.komentiranje.style.display = "none";
    };
    xhr.send();
})