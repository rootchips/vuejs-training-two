<template>
	<span v-if="isView == false">
		<div v-if="isEdit == false">
			<form @submit.prevent="submit">
				<strong class="mb-5">Create New Post</strong>
				<div class="form-group mt-3">
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

				<div class="form-group">
					<label for="category">Category</label>
					<select @change="loadSubCategory" class="form-control" v-model="category">
						<option value="">Please select</option>
						<option v-for="category in categories" :value="category.id">{{ category.name }}</option>
					</select>
					<template v-if="errors && errors['category']">
						<span class="text-danger">{{ errors['category'][0] }}</span>
					</template>
				</div>

				<div class="form-group">
					<label for="category">Sub Category</label>
					<select class="form-control" v-model="sub_category">
						<option value="">Please select</option>
						<option v-for="subcategory in sub_categories" :value="subcategory.id">{{ subcategory.name }}</option>
					</select>
					<template v-if="errors && errors['sub_category']">
						<span class="text-danger">{{ errors['sub_category'][0] }}</span>
					</template>
				</div>


				<button type="submit" class="btn btn-success">Submit</button>
			</form>
		</div>
		<div v-else>
			<form @submit.prevent="update">
				<strong class="mb-5">Edit Post</strong>
				<div class="form-group mt-3">
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
				<button type="submit" class="btn btn-secondary">Update</button>
			</form>
		</div>

		<div class="mt-5">
			<table class="table table-bordered">
				<thead class="thead-light">
					<th>#</th>
					<th>Title</th>
					<th>Content</th>
					<th>Category</th>
					<th>Sub Category</th>
					<th>Action</th>
				</thead>
				<tbody v-if="posts && posts.length > 0">
					<tr v-for="(post,index) in posts">
						<td>{{ index + 1 }}</td>
						<td>{{ post.title }}</td>
						<td>{{ post.content }}</td>
						<td>{{ post.sub_category.category.name }}</td>
						<td>{{ post.sub_category.name }}</td>
						<td>
							<button type="button" @click="view(post)" class="btn btn-primary">View</button>
							<button type="button" @click="edit(post)" class="btn btn-secondary">Edit</button>
							<button type="button" @click="deletePost(post)" class="btn btn-danger">Delete</button>

						</td>
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
				categories: [],
				sub_categories: [],
				category: "",
				sub_category: "",
				post: {
					id: null,
					title: null,
					content: null,
				},
				isView: false,
				isEdit: false,
			}
		},

		// Untuk trigger function apabila page di reload 
		mounted() {
			this.load()
		},

		methods: {
			async load() {
				const [posts, categories] = await Promise.all([
					axios.get('/api/posts'), // Load Post
					axios.get('/api/data/category'), // Load Category
				])

				this.posts = posts.data
				this.categories = categories.data
			},

			async loadSubCategory() {
				// Load Sub Category from API (trigger from on change select)
				this.sub_category = ""

				const { data } = await axios.get(`/api/data/category/sub/${this.category}`)

				this.sub_categories = data
			},

			async submit() {
				try {
					const { data } = await axios.post('/api/posts', {
						post: this.post,
						category: this.category,
						sub_category: this.sub_category
					})

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

			async update() {
				try {
					const { data } = await axios.put(`/api/posts/${this.post.id}`, {
						post: this.post,
					});

					// Reload Data
					this.load()

					// Set is edit to false
					this.isEdit = false

					// Hide validation
					this.errors = null

					// Empty the input
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

			edit(post) {
				this.isEdit = true

				this.post.id = post.id
				this.post.title = post.title
				this.post.content = post.content

			},

			async deletePost(post) {

				var alert = confirm('Are sure you want to delete this post?')

				if (alert == true) {
					try {
						const { data } = await axios.delete(`/api/posts/${post.id}`)

						// Reload table
						this.load()
					} catch (e) {

					}
				}
			},

			back() {
				this.isView = false
			}
		},

	}
</script>