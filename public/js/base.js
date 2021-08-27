// Wait until everything is loaded
document.addEventListener('DOMContentLoaded', function() {
    const const_showcase = document.getElementById('showcase');
    const const_showcase_gamecard_template = document.getElementById('preset-showcase-gamecard-content');

    if (const_showcase != null) {
        let clone_showcase_gamecard_template = const_showcase_gamecard_template.cloneNode(true);
        const_showcase.appendChild(clone_showcase_gamecard_template.content.querySelector('li.showcase-gamecard-content'));
    }
});