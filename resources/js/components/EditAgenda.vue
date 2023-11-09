<template lang="">
    <form @submit.prevent="editSubject" class="bg-gray-300 bg-opacity-80 w-screen h-screen justify-center items-center flex z-10 absolute top-0 left-0 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-4 w-1/3 font-roboto_medium max-h-[40rem] overflow-auto">
            <div class="flex justify-between w-full text-2xl p-3">
                <div class="flex gap-3">
                    <i class="bi bi-pencil-square flex h-full items-center"></i>
                    <input :disabled="loading"
                        type="text" id="subject" required
                        maxlength="255" v-model="subject"
                        class="border-0 border-b-2 border-black text-3xl w-full"
                    />
                </div>
                <div>
                    <button type="button" class="bi bi-x-lg" @click="editAgendaTrigger" :disabled="loading"></button>
                </div>
            </div>
            <div>
                <hr>
            </div>
            <div class="px-3">
                <div v-for="(item, index) in todo_items"
                :key="item"
                class="flex justify-between items-center w-full text-2xl p-2 gap-2">
                    <input :disabled="loading"
                        required
                        v-model="item.todo_name"
                        placeholder="Input Task"
                        type="text"
                        class="border-0 w-full text-1xl"
                    />
                    <div class="flex gap-4">
                        <button type="button" class="bi bi-trash3" @click="removeRow(index)" :disabled="loading"></button>
                    </div>
                </div>
                <div class="flex justify-center py-3">
                    <button type="button"
                        class="border-2 border-[#9C9C9C] rounded-lg py-2 w-full font-medium text-[#9C9C9C] text-lg"
                        @click="addRow" :disabled="loading">+ Add Task
                    </button>
                </div>
            </div>
            <div class="flex p-3 gap-2 overflow-x-auto w-full">
                <div v-for="(item, index) in todo_files" class="border-2 border-[#934DFF] rounded-lg h-10 w-25 flex  justify-center items-center relative group text-[#AD77FF] px-2">
                    <!-- on hover -->
                    <div class="w-full h-full rounded-lg items-center justify-center text-red-500 absolute top-0 left-0 backdrop-blur-sm hidden group-hover:flex">
                        <button type="button" class="bi bi-trash3" @click="deleteFile(index)"></button> 
                    </div>
                    <p class="truncate text-ellipsis">{{ item.file_name.split(".")[0] }}</p>
                    <p>{{ item.file_name.split(".")[item.file_name.split(".").length-1] }}</p>
                </div>
                <div class="w-full">
                    <input type="file" class="hidden" id="uploads" @change="fileHandler(e)" accept="image/jpg, image/png, application/pdf, application/vnd.ms-excel" :disabled="loading" />
                    <label for="uploads" class="border-2 border-[#934DFF] rounded-lg hover:cursor-pointer h-10 w-10 flex justify-center items-center">
                        <i class="bi bi-plus-lg"></i>
                    </label>
                </div>
            </div>
            <div class="flex gap-2 justify-end pt-4">
                <button type="button" 
                    class="border-2 py-2 px-3 rounded-lg text-lg"
                    @click="editAgendaTrigger" :disabled="loading" 
                    :style="{
                        'border-color': loading ? '#CECECE' : '#5A00E3',
                        'color': loading ? '#CECECE' : '#5A00E3'
                        }"
                    >Cancel
                </button>
                <button :disabled="loading"
                    class="border-2 border-[#5A00E3] bg-[#5A00E3] px-3 py-2 rounded-lg text-lg text-white">
                    <div v-if="loading" class="flex justify-center items-center gap-2">
                        <Spinner /><p> Saving...</p>
                    </div>
                    <p v-else>Save</p>
                </button>
            </div>
        </div>
    </form>
</template>
<script>
import Spinner from './Spinner.vue'
export default {
    components: {
        Spinner
    },
    props: {
        editAgendaTrigger: Function,
        todo: Object,
    },
    data() {
        return {
            todo_items: this.$props.todo.todo_items,
            todo_files: this.$props.todo.files,
            subject: this.$props.todo.subject_name,
            deleted_items: [],
            deleted_files: [],
            upload_requests: [],
            loading: false,
        }
    },
    methods: {
        addRow() {
            let blank = { todo_name: "" }
            this.todo_items.push(blank)
        },
        removeRow(index) {
            this.deleted_items.push(this.todo_items[index].id)
            this.todo_items.splice(index, 1)
        },
        deleteFile(index) {
            this.deleted_files.push(this.todo_files[index].id)
            this.todo_files.splice(index, 1)
        },
        fileHandler() {
            this.todo_files.push({
                "file": uploads.files[0],
                "file_name": uploads.files[0].name
            })
        },
        editSubject(){
            this.loading = true
            const id = this.$props.todo.id
            if(this.$props.todo.subject_name !== this.subject){
                axios.patch(`/api/subjects/${id}`, {
                    "subject_name": this.subject
                })
                .catch((error) => {
                    console.error(error)
                })
            }
            this.editTasks(id)
            this.editFiles(id)
            Promise.all(this.upload_requests)
            .then(()=>
            {
                this.loading = false
                this.$props.editAgendaTrigger()
            })
        },
        editTasks(id){
            for(let i=0;i<this.deleted_items.length;i++){
                axios.delete(`/api/todo_items/${this.deleted_items[i]}`)
            }
            for (let i=0;i<this.todo_items.length;i++) {
                if(this.todo_items[i].id){
                    axios.patch(`/api/todo_items/${this.todo_items[i].id}`, {
                        "todo_name": this.todo_items[i].todo_name
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                } else {
                    axios.post("/api/todo_items", {
                        "todo_name": this.todo_items[i].todo_name,
                        "subject_id": id
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                }
            }
        },
        editFiles(id){
            for(let i=0;i<this.deleted_files.length;i++){
                axios.delete(`/api/files/${this.deleted_files[i]}`)
            }
            for (let i=0;i<this.todo_files.length;i++) {
                if(!this.todo_files[i].id){
                    let formData = new FormData()
                    formData.append("files", this.todo_files[i].file)
                    formData.append("subject_id", id)
                    this.upload_requests.push(axios.post("/api/files", formData))
                }
            }
        }
    },
}
</script>
