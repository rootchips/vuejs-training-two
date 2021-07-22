<template>
	<span v-if="isView == false">
		<div>
			<form @submit.prevent="submit">
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" v-model="post.title">
					<template v-if="errors && errors['post.title']">
						<span class="text-danger">{{ errors['post.title'][0] }}</span>
					</template>
				</div>
				<div class="form-group">
					<label for="content">Content</label>
					<input type="text" class="form-control" v-model="post.content">
					<template v-if="errors && errors['post.content']">
						<span class="text-danger">{{ errors['post.content'][0] }}</span>
					</template>
				</div>
				<button type="submit" class="btn btn-success">Post</button>
			</form>
		</div>
		<div class="mt-5">
			<table class="table table-bordered">
				<thead class="thead-light">
					<th>#</th>
					<th>Title</th>
					<th>Content</th>
					<th>Action</th>
				</thead>
				<tbody v-if="posts && posts.length > 0">
					<tr v-for="(post,index) in posts">
						<td>{{ index + 1 }}</td>
						<td>{{ post.title }}</td>
						<td>{{ post.content }}</td>
						<td><button type="button" @click="view(post)" class="btn btn-primary">View</button></td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td colspan="4" class="text-center">No data found</td>
					</tr>
				</tbody>
			</table>
		</div>
	</span>
	<span v-else>
		<p>Title: {{ post.title }}</p>
		<p>Content: {{ post.content }}</p>

		<button @click="back" class="btn btn-primary">Back</button>
	</span>
</template>

<script>
	export default {
		data() {
			return {
				errors: null,
				posts: [],
				post: {
					title: null,
					content: null,
				},
				isView: false,
			}
		},

		// Untuk trigger function apabila page di reload 
		mounted() {
			this.load()
		},

		methods: {
			async load() {
				const { data } = await axios.get('/api/posts');

				this.posts = data
			},

			async submit() {
				try {
					const { data } = await axios.post('/api/posts', {
						post: this.post
					});

					// Apabila data validation hit dekat sini, dia akan stop dekat sini
					
					this.posts.push(data)

					this.errors = null

					this.post = {
						title: null,
						content: null
					}
				} catch (error) {
					this.errors = error.response.data.errors
				}
			},

			view(post) {
				this.isView = true

				this.post.title = post.title
				this.post.content = post.content
			},

			back() {
				this.isView = false
			}
		},

	}
</script>