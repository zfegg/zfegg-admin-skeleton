import { BaseRootSchema } from "@moln/data-source";

export interface Book {
    id: number,
    name: string,
    barcode: string,
    group: Record<any, any>
}


export const book = {
    type: "object",
    "primaryKey": "id",
    "properties": {
        "id": {
            "type": "number",
            "readOnly": true,
        },
        "name": {
            "type": "string",
            "title": "名称",
        },
        "barcode": {
            "type": "integer",
            "title": "条形码",
        },
        "created_at": {
            "type": "string",
            "title": "创建时间",
            "format": "date-time",
            "readOnly": true,
        },
        "status": {
            "type": "integer",
            "title": "状态"
        },
    },
    required: ["name", "barcode",]
} as BaseRootSchema