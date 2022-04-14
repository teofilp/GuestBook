if (!getCookie("userId")) window.location = "../login";

let hidden = true;
let pagination = {
  currentPage: 1,
  pages: 1,
};

document.getElementById("toggle-search").addEventListener("click", () => {
  document.getElementById("filter-box").style.display = hidden
    ? "flex"
    : "none";
  hidden = !hidden;
});

const nextPage = () => {
  if (pagination.currentPage === pagination.pages) return;
  pagination.currentPage++;
  fetchItems();
};

const previousPage = () => {
  if (pagination.currentPage === 1) return;
  pagination.currentPage--;
  fetchItems();
};

const getItemsAsHTML = (items) => {
  return items
    .map(
      (x) => `
          <div class="guest-book-item">
            <div class="item-content">
              <div class="title">
                <h2>${x.Title}</h2>
                <div>
                    <ion-icon name="create-outline" onclick="handleEdit(${
                      x.Id
                    })"></ion-icon>
                    <ion-icon name="trash-outline" onclick="handleDelete(${
                      x.Id
                    })"></ion-icon>
                </div>
              </div>
              <cite
                >"${x.Comment}"
                <span class="author">&#8212;${x.Author}</span>
              </cite>
              <span class="created-at">${x.Date.replaceAll("-", ".")}</span>
            </div>
          </div>`
    )
    .reduce((acc, curr) => acc + curr, "");
};

const handleDelete = (id) => {
  if (confirm(`Are you sure you want to delete record with id ${id}`)) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status === 200) {
        alert("Record deleted successfully!");
        fetchItems();
      }
    };
    xhttp.open("DELETE", `/guest-book/GuestBooks.php?id=${id}`);
    xhttp.send();
  }
};

const handleEdit = (id) => {
  window.location = `../edit-guest-book?id=${id}`;
};

const fetchItems = () => {
  const search = document.getElementById("search").value;
  const xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status === 200) {
      const data = JSON.parse(this.responseText);
      const { items, pages } = data;

      pagination.pages = pages;
      pagination.currentPage =
        pagination.currentPage > pages ? pages : pagination.currentPage;

      const itemsHTML = getItemsAsHTML(items);

      document.getElementById("guest-books-list").innerHTML = itemsHTML;
      setPagination();
    }
  };

  xhttp.open(
    "GET",
    `/guest-book/GuestBooks.php?search=${search}&page=${pagination.currentPage}`
  );
  xhttp.send();
};

const setPagination = () => {
  document.getElementById(
    "pagination-details"
  ).innerText = `${pagination.currentPage} / ${pagination.pages}`;
};

document.addEventListener("DOMContentLoaded", () => {
  setPagination();
  fetchItems();
});
