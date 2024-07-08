document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.navigation a').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
          e.preventDefault();

          const targetId = this.getAttribute('href').substring(1);
          const targetElement = document.getElementById(targetId);

          if (targetElement) {
              const offsetTop = targetElement.offsetTop;

              // Scroll to the target element using CSS transitions
              smoothScrollTo(offsetTop);
          }
      });
  });
});

function smoothScrollTo(offsetTop) {
  const startY = window.pageYOffset;
  const distance = offsetTop - startY;
  const duration = 800; // Adjust the duration as needed

  let start = null;
  window.requestAnimationFrame(function step(timestamp) {
      if (!start) start = timestamp;
      const progress = timestamp - start;
      const scrollY = startY + distance * easeInOutQuad(progress / duration);
      window.scrollTo(0, scrollY);
      if (progress < duration) window.requestAnimationFrame(step);
  });
}

function easeInOutQuad(t) {
  return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
}


/* Add to cart 
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.addtocart');
  
    buttons.forEach(button => {
      let added = false;
      button.addEventListener('click', () => {
        const done = button.querySelector('.done');
        const pretext = button.querySelector('.pretext');
  
        if (!added) {
          done.style.transform = "translate(0)";
          pretext.style.transform = "translate(-110%)";
          added = true;
  
          // Disable the button to prevent it from being clicked again
          button.disabled = true;
        }
      });
    });
  });*/
  
  

/* Open and Close side cart */
let openshopping = document.querySelector('.shopping');
let closeshopping = document.querySelector('.closeshopping');
let body = document.querySelector('body');

openshopping.addEventListener('click', () => {
    body.classList.add('active');
});

closeshopping.addEventListener('click', () => {
    body.classList.remove('active');
});

/* */



