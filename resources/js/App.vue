<script setup>
import axios from "axios"
import { ref } from 'vue';
import { watchEffect } from 'vue';
import { watch } from 'vue';
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router'
import { Modal } from 'flowbite';
import { onMounted } from 'vue'
import { computed } from "vue";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

const config = {
    itemsToShow: 1
}



const response = ref();
const router = useRouter()
const route = useRoute()
const rowPerPage = ref(10);
const page = ref(1);

const brands = ref({})
const models = ref({})
const models_filtered = ref({})

const engines = ref({})
const drives = ref({})
let modal
const image_path = ref('')


const filter_model = ref(0)
const filter_brand = ref(0)
const filter_engine = ref(0)
const filter_drive = ref(0)




const query = computed(() => route.query)

watch(query, newquery => {
    if (page.value != newquery.currentPage) page.value = newquery.currentPage
    if (rowPerPage.value != newquery.perPage) rowPerPage.value = newquery.perPage
    if (filter_model.value != newquery.filter_model) filter_model.value = newquery.filter_model
    if (filter_brand.value != newquery.filter_brand) filter_brand.value = newquery.filter_brand
    if (filter_engine.value != newquery.filter_engine) filter_engine.value = newquery.filter_engine
    if (filter_drive.value != newquery.filter_drive) filter_drive.value = newquery.filter_drive

}
)

onMounted(() => {

    modal = new Modal(document.getElementById('default-modal'), {}, {});

    watchEffect(fetchRows);
})



const setParams = () => {
    console.log(page.value)

    router.replace({
        query: {
            currentPage: page.value,
            perPage: rowPerPage.value,
            filter_model: filter_model.value,
            filter_brand: filter_brand.value,
            filter_engine: filter_engine.value,
            filter_drive: filter_drive.value,
        }
    })


}



const fetchRows = async () => {
    try {
        const params = {
            currentPage: page.value,
            perPage: rowPerPage.value,
            filter_model: filter_model.value,
            filter_brand: filter_brand.value,
            filter_engine: filter_engine.value,
            filter_drive: filter_drive.value,

        }
        response.value = await axios.get("/getCarsList", { params })

        setParams()
    } catch (error) {

        console.log(error);
    }
};


const getGuides = async () => {

    try {
        const data = await axios.get("/getGuides")
        brands.value = data.data['brands']
        models.value = data.data['car_models']
        models_filtered.value = models.value
        engines.value = data.data['engine_types']
        drives.value = data.data['dive_types']

    } catch (error) {

        console.log(error);
    }
};




const setPage = (newPage) => {
    page.value = newPage
}


const selectBrand = () => {
    if (filter_brand.value == 0) models_filtered.value = models.value
    else {
        models_filtered.value = models.value.filter(
            (v) => v.brand_id == filter_brand.value
        )
    }
}


getGuides()




</script>





<template>
    <div class=" px-4">
        <table class="w-full text-left table-auto min-w-max">
            <thead>
                <tr class="odd:bg-gray-100">
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Марка
                        </p>
                        <select @change="selectBrand" v-model="filter_brand"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(brand) in brands" :key="brand" :value="brand.id"> {{ brand.title }}</option>
                        </select>

                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Модель
                        </p>
                        <select v-model="filter_model"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(model) in models_filtered" :key="model" :value="model.id"> {{ model.title }}
                            </option>
                        </select>

                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Год выпуска
                        </p>
                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Цена
                        </p>
                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Тип двигателя
                        </p>
                        <select v-model="filter_engine"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(engine) in engines" :key="engine" :value="engine.id"> {{ engine.title }}
                            </option>
                        </select>
                    </th>

                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Тип привода
                        </p>
                        <select v-model="filter_drive"
                            class="bg-gray-50 border w-full mt-5 h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Все</option>
                            <option v-for="(drive) in drives" :key="drive" :value="drive.id"> {{ drive.title }}</option>
                        </select>
                    </th>
                    <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50 max-w-24">
                        <p
                            class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Изображения
                        </p>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr v-if="response" v-for="(line) in response.data.data" :key="line" class="odd:bg-gray-100">
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900"> {{
                            line.id
                        }}
                            {{ line.car_model.brand.title }}

                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ line.car_model.title }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ new Date(line.manufactured_year).getFullYear() }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ line.price }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-medium leading-normal text-blue-gray-900">
                            {{ line.engine_type.title }}
                        </p>
                    </td>
                    <td class="p-4 border-b border-blue-gray-50">
                        <p class="block font-sans text-sm antialiased font-medium leading-normal text-blue-gray-900">
                            {{ line.drive_type.title }}
                        </p>

                    </td>

                    <td class="p-4 border-b border-blue-gray-50 max-w-24">
                        <!--Carousel v-bind="config">
                            <Slide v-for="(image) in line.images" :key="image">
                                <div @click="image_path = image.path; modal.show()">
                                    <img class="h-auto max-w-20" :src="image.path">
                                </div>
                            </Slide>

                            <template #addons>
                                <Pagination />

                            </template>
                        </Carousel-->
                    </td>
                </tr>


            </tbody>
        </table>


        <div v-if="response" class="inline-flex w-full justify-between">
            <ul class="inline-flex -space-x-px text-base h-10 mt-8">
                <li>
                    <div @click="setPage(1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        << </div>
                </li>
                <li v-if="response.data.current_page > 2">
                    <div @click="setPage(response.data.current_page - 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 2 }}</div>
                </li>
                <li v-if="response.data.current_page > 1">
                    <div @click="setPage(response.data.current_page - 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page - 1 }}</div>
                </li>
                <li>
                    <div aria-current="page"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                        {{
                            response.data.current_page }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page">
                    <div @click="setPage(response.data.current_page + 1)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 1 }}</div>
                </li>
                <li v-if="response.data.current_page < response.data.last_page - 1">
                    <div @click="setPage(response.data.current_page + 2)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        {{
                            response.data.current_page + 2 }}</div>
                </li>
                <li>
                    <div @click="setPage(response.data.last_page)"
                        class="cursor-pointer flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        >></div>
                </li>
            </ul>

            <div class="inline-flex mt-8">
                <label for="perpage" class="block me-3 my-auto text-sm font-medium text-gray-900 dark:text-white">Машин на
                    странице </label>

                <select id="perpage" v-model="rowPerPage"
                    class="bg-gray-50 border  h-10 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="10"> 10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>

                </select>
            </div>
        </div>
    </div>



    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <img @click="modal.hide()" class="h-auto max-w-xl rounded-lg shadow-xl dark:shadow-gray-800"
                    :src="image_path" alt="image description">
            </div>
        </div>
    </div>
</template>