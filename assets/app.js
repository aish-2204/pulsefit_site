const nav = document.querySelector(".nav");
const navToggle = document.querySelector(".nav-toggle");
const navLinks = document.querySelectorAll(".links a");

if (navToggle && nav) {
  navToggle.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("nav-open");
    navToggle.setAttribute("aria-expanded", String(isOpen));
  });
}

if (navLinks.length > 0) {
  const path = window.location.pathname;
  const currentFile = path.split("/").pop();
  navLinks.forEach((link) => {
    const href = link.getAttribute("href") || "";

    // Check if this link should be active
    let isActive = false;

    // Home: only when at root index.php (not in products/)
    if (href.endsWith("index.php") && !href.includes("products") && !path.includes("/products/") && (currentFile === "" || currentFile === "index.php")) {
      isActive = true;
    }
    // Products: only when path includes /products/
    else if (href.includes("products/index.php") && path.includes("/products/")) {
      isActive = true;
    }
    // Exact match for other pages
    else if (href === currentFile && currentFile !== "index.php") {
      isActive = true;
    }

    if (isActive) {
      link.classList.add("active");
    }
  });
}

const revealTargets = document.querySelectorAll(".card, .panel, .badges, .actions, .page-head");
if (revealTargets.length > 0) {
  revealTargets.forEach((el) => el.classList.add("reveal"));

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          obs.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12 }
  );

  revealTargets.forEach((el) => observer.observe(el));
}

