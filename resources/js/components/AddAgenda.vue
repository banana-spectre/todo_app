<template lang="">
    <form @submit.prevent="addSubject"
        class="bg-gray-300 bg-opacity-80 w-screen h-screen justify-center items-center flex z-10 absolute top-0 left-0 backdrop-blur-sm"
    >
        <div class="bg-white rounded-3xl p-4 font-roboto_medium w-1/3 max-h-[40rem] overflow-auto">
            <div class="flex justify-between w-full text-2xl p-3">
                <div class="flex gap-3">
                    <i class="bi bi-pencil-square flex h-full items-center"></i>
                    <input :disabled="loading"
                        type="text" id="subject" required
                        placeholder="Input Title"
                        maxlength="255"
                        class="border-0 border-b-2 border-black text-3xl w-full"
                    />
                </div>
                <div class="flex items-center justify-center">
                    <button type="button" class="bi bi-x-lg" :disabled="loading" @click="modalHandler"></button>
                </div>
            </div>
            <div>
                <hr />
            </div>
            <div class="px-3">
                <div
                    v-for="item in count"
                    :key="item"
                    class="flex justify-between items-center w-full text-2xl p-2 gap-2"
                >
                    <input :disabled="loading"
                        v-model="tasks[item]" required
                        placeholder="Input Task"
                        type="text"
                        class="border-0 w-full text-1xl"
                    />
                    <button type="button" class="bi bi-trash3" @click="removeRow(item)" :disabled="loading"></button>
                </div>
                <div class="flex justify-center py-3">
                    <button type="button"
                        class="border-2 border-[#9C9C9C] rounded-lg py-2 w-full font-medium text-[#9C9C9C] text-lg"
                        @click="addRow" :disabled="loading"
                    >
                        + Add Task
                    </button>
                </div>
            </div>
            <div class="flex p-3 gap-2 overflow-x-auto w-full">
                <div v-for="(file, index) in files" class="border-2 border-[#934DFF] rounded-lg h-10 w-25 flex justify-center items-center relative group text-[#AD77FF] px-2">
                    <!-- on hover -->
                    <div class="w-full h-full rounded-lg items-center justify-center text-red-500 absolute top-0 left-0 backdrop-blur-sm hidden group-hover:flex">
                        <button type="button" class="bi bi-trash3" @click="deleteFile(index)"></button> 
                    </div>
                    <p class="truncate text-ellipsis">{{ file.name.split(".")[0] }}</p>
                    <p>{{ file.name.split(".")[file.name.split(".").length-1] }}</p>
                </div>
                <div class="w-full">
                    <input type="file" class="hidden" id="uploads" @change="fileHandler(e)" :disabled="loading" accept="image/jpg, image/png, application/pdf, application/vnd.ms-excel" />
                    <label for="uploads" class="border-2 border-[#934DFF] rounded-lg hover:cursor-pointer h-10 w-10 flex justify-center items-center">
                        <i class="bi bi-plus-lg"></i>
                    </label>
                </div>
            </div>
            <div class="flex gap-2 justify-end pt-4">
                <button type="button" 
                    class="border-2 py-2 px-3 rounded-lg text-lg"
                    @click="modalHandler" :disabled="loading" 
                    :style="{
                        'border-color': loading ? '#CECECE' : '#5A00E3',
                        'color': loading ? '#CECECE' : '#5A00E3'
                        }"
                >
                    Cancel
                </button>
                <button
                    class="border-2 border-[#5A00E3] bg-[#5A00E3] px-3 py-2 rounded-lg text-lg text-white"
                    :disabled="loading"
                >
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
import Spinner from './Spinner.vue';

export default {
    props: {
        modalHandler: Function,
    },
    components: {
        Spinner
    },
    data() {
        return {
            count: 1,
            tasks: [],
            files: [],
            upload_requests: [],
            loading: false,
        }
    },
    methods: {
        addRow() {
            this.count++
        },
        removeRow(index) {
            this.count--
            this.tasks.splice(index, 1)
        },
        fileHandler() {
            this.files.push({
                "file": uploads.files[0],
                "name": uploads.files[0].name
            })
        },
        deleteFile(index) {
            this.files.splice(index, 1)
        },
        addSubject() {
            this.loading = true
            axios.post("/api/subjects", {
                "subject": subject.value
            })
            .then((subject) => {
                this.addTask(subject.data.id)
                this.uploadFile(subject.data.id)
                Promise.all(this.upload_requests)
                .then(()=> {
                    this.loading = false
                    this.$props.modalHandler()
                })
            })
            .catch((error) => {
                console.error(error)
            })
            
        },
        addTask(id) {
            for (let i=1;i<this.tasks.length;i++) {
                axios.post("/api/todo_items", {
                    "todo_name": this.tasks[i],
                    "subject_id": id
                })
                .catch((error) => {
                    console.error(error)
                })
            }
        },
        uploadFile(id) {
            for (let i=0;i<this.files.length;i++) {
                let formData = new FormData()
                formData.append("files", this.files[i].file)
                formData.append("subject_id", id)
                this.upload_requests.push(axios.post("/api/files", formData))
            }
        }
    },
};
</script>
