const Tasks = (($) => {
  const getTasks = () => {
    $.ajax({
      url: ajax_object.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "get_tasks",
      },
      success: function (response) {
        if (response.type === "error") {
          $(".todo-list__error").show();
          return;
        }

        if (response.data.length === 0) {
          $(".todo-list__empty").show();
          return;
        }

        let container = $(".todo-list__tasks");

        response.data.forEach((task) => {
          container.append(_getTaskTemplate(task));
        });
      },
    });
  };

  const _getTaskTemplate = (task) => {
    return `
    <li class="todo-list__tasks--item">
      <h3>${task.title}</h3>
      <p>${task.description}</p>
    </li>
    `;
  };

  return {
    init: () => {
      getTasks();
    },
  };
})(jQuery);
