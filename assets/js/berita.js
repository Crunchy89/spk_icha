get();
$("#links").on("click", ".page", function () {
  let link = $(this).data("url");
  get(link);
});
async function get(link = null, data = null) {
  if (!data) {
    let data = new FormData();
    let cari = $("#cari").val();
    data.append("cari", cari);
  }
  await axios
    .post(`${link ? link : href}`, data)
    .then((res) => {
      let links = "";
      let berita = "";
      res.data.links.map((row) => {
        links += `<li class="${row.active ? "active" : null}"><a ${
          row.url ? `data-url="${row.url}"` : ""
        } type="button" class="page">${row.label}</a></li>`;
      });
      res.data.data.map((row) => {
        let tanggal = new Date(row.created_at);
        let bulan = tanggal.toLocaleString("default", { month: "short" });
        berita += `
                                <article class="entry">
                                <div class="entry-img">
                                  <img src="${
                                    row.foto
                                  }" alt="" class="img-fluid w-100">
                                </div>
                                <h2 class="entry-title">
                                  <a href="${to}/${row.id}">${row.judul}</a>
                                </h2>
                                <div class="entry-meta">
                                  <ul>
                                    <li class="d-flex align-items-center"><i class="icofont-user"></i> <a href="${to}/${
          row.id
        }'">Admin</a></li>
                                    <li class="d-flex align-items-center"><i class="icofont-wall-clock"></i> <a href="${to}/${
          row.id
        }"><time datetime="">${bulan}, ${
          tanggal.getDate() + "-" + tanggal.getFullYear()
        }</time></a></li>
        <li class="d-flex align-items-center"><i class="icofont-comment"></i> <a href="${
          to + "/" + row.id
        }">${row.komen} Komentar</a></li>
                                  </ul>
                                </div>
                                <div class="entry-content">
                                  <p>
                                      ${row.deskripsi_berita}
                                  </p>
                                  <div class="read-more">
                                    <a href="${to}/${row.id}">Selengkapnya</a>
                                  </div>
                                </div>
                                </article>
                    `;
      });
      $("#links").html(links);
      $("#berita").html(berita);
    })
    .catch((err) => {
      console.log(err.response.data);
    });
}
$("#filter").submit(function (e) {
  e.preventDefault();
  let data = new FormData(this);
  get(null, data);
});
