/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from "@wordpress/block-editor";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";
import "../../../../src/scss/styles.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	// fetch data from rest endpoint
	const [tasks, setTasks] = useState([]);

	useEffect(() => {
		fetch("/wp-json/tasks/v1/all")
			.then((response) => response.json())
			.then((data) => {
				setTasks(data);
			});
	}, []);

	return (
		<section {...useBlockProps()}>
			<h2>ToDo List</h2>
			<ul class="todo-list__tasks">
				{tasks.map((task) => (
					<li key={task.id} class="todo-list__tasks--item">
						<h3>{task.title}</h3>
						<p>{task.description}</p>
					</li>
				))}
			</ul>
		</section>
	);
}
