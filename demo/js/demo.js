// smoothy anchor
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth', block: 'start', inline: "end"
        });
         // document.querySelector(this.getAttribute('href')).scrollTop += 110;
          
        
          
    });
});


