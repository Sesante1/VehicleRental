(() => {
    const route = (event) => {
        event = event || window.event;
        event.preventDefault();
        window.history.pushState({}, "", event.target.href);
        handleLocation();

        document.querySelectorAll('#menuList li a').forEach(link => {
            link.classList.remove('active');
        });

        event.currentTarget.classList.add('active');
    };

    const routes = {
        "/": "/pages/home.php",
        "/Cars": "/pages/listCars.php",
        "/MyBookings": "/pages/bookings.php",
        "/MyCars": "/pages/myCars.php",
        "/login": "login.php",
        "/signup": "signup.php",
        "/message": "users.php",
        "/chat": "chat.php",
        "/listCar": "/pages/list-your-car.php",
        "404": "/pages/404.php",
    };

    const getBasePath = (pathname) => {

        return pathname.split("?")[0];
    };

    const handleLocation = async () => {
        let fullPath = window.location.pathname + window.location.search;
        let path = getBasePath(window.location.pathname);

        if (!routes[path]) {
            window.history.replaceState({}, "", "/");
            path = "/";
        }

        const routePath = routes[path] || routes["404"];

        try {
            const html = await fetch(routePath + window.location.search).then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.text();
            });

            const mainPage = document.getElementById("main-page");
            mainPage.innerHTML = html;

            const scripts = mainPage.querySelectorAll("script");
            scripts.forEach(oldScript => {
                const newScript = document.createElement("script");
                if (oldScript.src) {
                    newScript.src = oldScript.src;
                    newScript.type = oldScript.type || "text/javascript";
                    document.body.appendChild(newScript);
                } else {
                    newScript.textContent = oldScript.textContent;
                    document.body.appendChild(newScript);
                }
                oldScript.remove();
            });

            updateActiveLink(path);
        } catch (error) {
            console.error("Failed to load the page:", error);
            if (path !== "/") {
                window.history.replaceState({}, "", "/");
                handleLocation();
            }
        }
    };

    const updateActiveLink = (currentPath) => {
        document.querySelectorAll('#menuList li a').forEach(link => {
            link.classList.remove('active');

            let linkPath = link.getAttribute('href');

            if (linkPath !== '/' && !linkPath.startsWith('/')) {
                linkPath = '/' + linkPath;
            }

            if (currentPath === linkPath) {
                link.classList.add('active');
            }
        });
    };

    window.onpopstate = handleLocation;
    window.route = route;

    document.addEventListener("DOMContentLoaded", () => {
        // Don't force redirect to home page on load
        // Keep the current path from the URL
        handleLocation();
    });

})();

