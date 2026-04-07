const accessToken = 'YOUR_ACCESS_TOKEN_HERE';
const fields = 'id,media_type,media_url,permalink,timestamp';
const url = `https://graph.instagram.com/me/media?fields=${fields}&access-token=${accessToken}`;

fetch(url)
    .then(response => response.json())
    .then(data => {
        const feed = document.getElementById('insta-feed');
        // Limit to latest 12 posts
        const posts = data.data.slice(0, 12); 

        posts.forEach(post => {
            if (post.media_type !== 'VIDEO') {
                const anchor = document.createElement('a');
                anchor.href = post.permalink;
                anchor.target = '_blank';

                const img = document.createElement('img');
                img.src = post.media_url;
                
                anchor.appendChild(img);
                feed.appendChild(anchor);
            }
        });
        const menuToggle = document.getElementById('mobile-menu');
const navList = document.getElementById('nav-list');

menuToggle.addEventListener('click', () => {
    navList.classList.toggle('active');
});

// Close menu when a link is clicked
document.querySelectorAll('.navbar nav ul li a').forEach(link => {
    link.addEventListener('click', () => {
        navList.classList.remove('active');
    });
});
    })
    .catch(err => console.error('Error fetching Instagram feed:', err));