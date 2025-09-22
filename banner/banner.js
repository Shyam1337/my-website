// This script adds the blinking banner to the top of each page

// Create the banner element
const banner = document.createElement('div');
banner.id = 'blinking-banner';
banner.style.backgroundColor = 'transparent';
banner.style.padding = '0';
banner.style.marginTop = '0';
banner.style.height = '55px';
banner.style.width = '100%';
banner.style.display = 'flex';
banner.style.alignItems = 'center';
banner.style.justifyContent = 'center';
banner.style.color = 'white';
banner.style.fontSize = '16px';
banner.style.lineHeight = '1.5';

// Create the HTML content for the banner
banner.innerHTML = `
    <p class="special-offer">SPECIAL OFFER: BOOK YOUR 2025 & 2026 ADVENTURES!</p>
    <p class="line1">BOOK YOUR 2025 & 2026 ADVENTURES RISK FREE.</p>
`;

// Append the banner to the top of the body
document.body.insertBefore(banner, document.body.firstChild);

// Add blinking functionality
let currentIndex = 0;
const texts = banner.querySelectorAll('p');

function showNextText() {
    texts.forEach(text => text.classList.remove('active'));
    texts[currentIndex].classList.add('active');
    currentIndex = (currentIndex + 1) % texts.length;
}

// Show first text immediately
showNextText();

// Change text every 3 seconds
setInterval(showNextText, 3000);
