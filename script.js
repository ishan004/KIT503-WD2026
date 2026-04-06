const searchBtn = document.getElementById("searchBtn");
const clearBtn = document.getElementById("clearBtn");
const searchInput = document.getElementById("searchInput");
const typeFilter = document.getElementById("typeFilter");
const submissionList = document.getElementById("submissionList");
const noResults = document.getElementById("noResults");

const navLinks = document.querySelectorAll(".nav-link");

let currentPage = window.location.pathname.split("/").pop();

for (let i = 0; i < navLinks.length; i++) {
  let link = navLinks[i];
  let linkPage = link.getAttribute("href");

  if (linkPage === currentPage) {
    link.classList.add("active");
  }
}

let allSubmissions = [];

fetch("submissions.json")
  .then(function (response) {
    return response.json();
  })
  .then(function (data) {
    allSubmissions = data;
    showSubmissions(allSubmissions);
  })
  .catch(function () {
    submissionList.innerHTML = "<p>Could not load submissions.json</p>";
  });

searchBtn.addEventListener("click", function () {
  applyFilters();
});

clearBtn.addEventListener("click", function () {
  searchInput.value = "";
  typeFilter.value = "all";
  showSubmissions(allSubmissions);
});

function showSubmissions(list) {
  submissionList.innerHTML = "";

  if (list.length === 0) {
    noResults.style.display = "block";
    return;
  }

  noResults.style.display = "none";

  for (let i = 0; i < list.length; i++) {
    const item = list[i];

    const box = document.createElement("div");
    box.className = "submission-box";

    const title = document.createElement("h3");
    title.textContent = item.title;

    const author = document.createElement("p");
    author.innerHTML = "<strong>Author:</strong> " + item.author;

    const type = document.createElement("p");
    type.innerHTML = "<strong>Type:</strong> " + item.type;

    const button = document.createElement("button");
    button.className = "detailsBtn";
    button.type = "button";
    button.textContent = "View Details";

    const details = document.createElement("div");
    details.className = "more-info";
    details.style.display = "none";

    const detailsTitle = document.createElement("p");
    detailsTitle.innerHTML = "<strong>Title:</strong> " + item.title;

    const detailsAuthor = document.createElement("p");
    detailsAuthor.innerHTML = "<strong>Author:</strong> " + item.author;

    const detailsType = document.createElement("p");
    detailsType.innerHTML = "<strong>Type:</strong> " + item.type;

    const detailsEmail = document.createElement("p");
    detailsEmail.innerHTML = "<strong>Email:</strong> " + item.email;

    const detailsAbstract = document.createElement("p");
    detailsAbstract.innerHTML = "<strong>Abstract:</strong> " + item.abstract;

    details.appendChild(detailsTitle);
    details.appendChild(detailsAuthor);
    details.appendChild(detailsType);
    details.appendChild(detailsEmail);
    details.appendChild(detailsAbstract);

    button.addEventListener("click", function () {
      if (details.style.display === "none") {
        details.style.display = "block";
      } else {
        details.style.display = "none";
      }
    });

    box.appendChild(title);
    box.appendChild(author);
    box.appendChild(type);
    box.appendChild(button);
    box.appendChild(details);

    submissionList.appendChild(box);
  }
}

function applyFilters() {
  const searchText = searchInput.value.toLowerCase();
  const selectedType = typeFilter.value;
  let filteredList = [];

  for (let i = 0; i < allSubmissions.length; i++) {
    const item = allSubmissions[i];

    let matchSearch = false;
    let matchType = false;

    if (
      item.title.toLowerCase().includes(searchText) ||
      item.author.toLowerCase().includes(searchText) ||
      item.type.toLowerCase().includes(searchText) ||
      item.abstract.toLowerCase().includes(searchText)
    ) {
      matchSearch = true;
    }

    if (selectedType === "all" || item.type === selectedType) {
      matchType = true;
    }

    if (matchSearch && matchType) {
      filteredList.push(item);
    }
  }

  showSubmissions(filteredList);
}
