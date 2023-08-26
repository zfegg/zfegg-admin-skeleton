import React, {FC, useState} from "react";
import {PageContainer} from "@ant-design/pro-layout";
import {Button, Space} from "antd";
import {
    DeleteButton,
    FormDrawer,
    ProColumnType,
    ProTable,
    useDataSource,
    useDataSourceBindSearch,
    valueEnumToOptions
} from "@zfegg/admin-data-source-components";
import {EditOutlined} from "@ant-design/icons";
import {Book} from "../models/book";
import {statusEnum} from "@/modules/book/constants";


const Books: FC = () => {
    const dataSource = useDataSource('book/books')
    const [visible, setVisible] = useState(false)
    const [itemId, setItemId] = useState<number>()


    const columns: ProColumnType<Book>[] = [
        {
            dataIndex: 'name',
            filterable: true,
        },
        {
            dataIndex: 'barcode',
            filterable: true,
        },
        {
            dataIndex: 'created_at',
            filterable: true,
        },
        {
            dataIndex: 'status',
            filterable: true,
            valueEnum: statusEnum,
        },
        {
            title: '操作',
            key: 'actions',
            render: (_, row) => {
                return (
                    <Space>
                        <Button size={"small"}
                                onClick={() => {
                                    setItemId(row.id)
                                    setVisible(true)
                                }}
                                type={"primary"}
                                icon={<EditOutlined/>}
                        />
                        <DeleteButton dataSource={dataSource} item={row}/>
                    </Space>
                )
            },
        }
    ];
    useDataSourceBindSearch(dataSource)

    return (
        <PageContainer extra={[
            <Button key={"add"} type={"primary"}
                    onClick={() => {
                        setVisible(true)
                        setItemId(undefined)
                    }}>新增</Button>
        ]}>
            <ProTable
                autoFetch={false}
                defaultSize={"small"}
                columns={columns}
                dataSource={dataSource}
            />
            <FormDrawer
                visible={visible}
                itemId={itemId}
                onClose={() => {
                    setVisible(false)
                }}
                dataSource={dataSource}
                uiProps={{
                    status: {
                        enumOptions: valueEnumToOptions(statusEnum),
                    }
                }}
            />
        </PageContainer>
    );
}


export default Books