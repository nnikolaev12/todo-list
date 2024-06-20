const Tasks = (($) => {
  const getAllTasks = () => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "get_tasks",
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError();
          return;
        }

        let container = $(".todo-list__tasks");
        container.html("");

        response.data.forEach((task) => {
          container.append(_getTaskTemplate(task));
        });
      },
    });
  };

  const toggleNewTaskForm = () => {
    $(".todo-list__add-btn").on("click", function () {
      $(".todo-list__add-task").slideToggle();
    });
  };

  const saveNewTask = () => {
    $(".todo-list__add-task").on("submit", function (e) {
      e.preventDefault();

      const taskName = $("#tldTitle");
      const taskDescription = $("#tldDescription");

      _createTask(taskName.val(), taskDescription.val());

      // empty inputs and hide form
      taskName.val("");
      taskDescription.val("");
      $(".todo-list__add-task").slideToggle();
    });
  };

  const toggleEditTaskForm = () => {
    $(".todo-list__tasks").on(
      "click",
      ".todo-list__tasks--item__edit",
      function () {
        const id = $(this).data("id");
        const title = $(this).parent().siblings().find("h3").text();
        const description = $(this).parent().siblings().find("p").text();

        // show form and fill inputs
        const form = $(".todo-list__edit-task");
        form.show();
        form.find("input[name='id']").val(id);
        form.find("input[name='title']").val(title);
        form.find("textarea").val(description);
      }
    );

    $(".todo-list__edit-task--close").on("click", function () {
      $(".todo-list__edit-task").hide();
    });
  };

  const updateTask = () => {
    $(".todo-list__edit-task").on("submit", function (e) {
      e.preventDefault();

      const id = $(this).find("input[name='id']").val();
      const title = $(this).find("input[name='title']").val();
      const description = $(this).find("textarea").val();

      _updateTask(id, title, description);

      $(".todo-list__edit-task").hide();
    });
  };

  const deleteTask = () => {
    $(".todo-list__tasks").on(
      "click",
      ".todo-list__tasks--item__delete",
      function () {
        const id = $(this).data("id");
        if (confirm("Are you sure?")) {
          _deleteTask(id);
        }
      }
    );
  };

  /**
   * Private methods
   */
  const _createTask = (title, description) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "create_task",
        title: title,
        description: description,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError();
          return;
        }

        getAllTasks();
      },
    });
  };

  const _updateTask = (id, title, description) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "update_task",
        id: id,
        title: title,
        description: description,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError();
          return;
        }

        getAllTasks();
      },
    });
  };

  const _deleteTask = (id) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "delete_task",
        id: id,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError();
          return;
        }

        getAllTasks();
      },
    });
  };

  const _getTaskTemplate = (task) => {
    return `
    <li class="todo-list__tasks--item">
      <div>
        <h3>${task.title}</h3>
        <p>${task.description}</p>
      </div>
      <div>
        <button class="todo-list__btn todo-list__btn--primary todo-list__tasks--item__edit" data-id="${task.id}">Edit</button>
        <button class="todo-list__btn todo-list__btn--secondary todo-list__tasks--item__delete" data-id="${task.id}">Delete</button>
      </div>
    </li>
    `;
  };

  const _throwError = () => {
    alert("Something went wrong!");
  };

  return {
    init: () => {
      getAllTasks();

      toggleNewTaskForm();
      saveNewTask();

      toggleEditTaskForm();
      updateTask();

      deleteTask();
    },
  };
})(jQuery);
