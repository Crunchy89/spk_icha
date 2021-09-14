$(document).ready(function () {
  $("#form").submit(function (e) {
    e.preventDefault();
    $(this).find("#spin").find("button").addClass("d-none");
    $(this).find("#spin").find(".text-center").removeClass("d-none");
    let url = $(this).data("url");
    let data = new FormData(this);
    axios
      .post(`${url}`, data)
      .then((res) => {
        $(this).find("#spin").find("button").removeClass("d-none");
        $(this).find("#spin").find(".text-center").addClass("d-none");
        table.ajax.reload();
        $("#modal").modal("hide");
        if (res.data.status) {
          toastr["success"](res.data.pesan);
        } else {
          toastr["error"](res.data.pesan);
        }
      })
      .catch((err) => {
        console.log(err.response.data.errors);
        $(this).find("#spin").find("button").removeClass("d-none");
        $(this).find("#spin").find(".text-center").addClass("d-none");
        toastr["error"]("Terjadi kesalahan pada server");
      });
  });
});
