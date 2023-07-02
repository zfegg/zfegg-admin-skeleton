import React, {Component} from "react";
import {PageContainer} from "@ant-design/pro-layout";
import {Button, Space} from "antd";
import {ColumnType, FormDrawer, Table, valueEnumToOptions} from "@zfegg/admin-data-source-components";
import {injectServices} from "@moln/react-ioc";
import {DataSource, Resources} from "@moln/data-source";
import {observer} from "mobx-react";
import ProCard from "@ant-design/pro-card";
import {DeleteOutlined, EditOutlined} from "@ant-design/icons";
import {Book} from "../models/book";
import {statusEnum} from "@/modules/book/constants";

interface CardProps {
    dataSource: DataSource<Book>,
}

const injection = injectServices((container) => {
    return {
        dataSource: container.get(Resources).create('book/v2/books').createDataSource(),
    }
})

@observer
class Books extends Component<CardProps> {

    state = {
        visible: false,
        itemId: undefined
    }

    handleRemove = async (row: Book) => {
        const {dataSource} = this.props;
        dataSource.remove(dataSource.get(row.id)!)
        await dataSource.sync();
        await dataSource.fetch();
    }

    render() {

        const {visible, itemId} = this.state;
        const {dataSource} = this.props;

        const columns: ColumnType<Book>[] = [
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
            },
            {
                title: '操作',
                key: 'actions',
                render: (_, row) => {
                    return (
                        <Space>
                            <Button size={"small"}
                                    onClick={() => this.setState({visible: true, itemId: row.id})}
                                    type={"primary"}
                                    icon={<EditOutlined />}
                            />
                            <Button size={"small"}
                                    onClick={() => this.handleRemove(row)}
                                    danger
                                    icon={<DeleteOutlined />} />
                        </Space>
                    )
                },
            }
        ];

        return (
            <PageContainer extra={[
                <Button key={"add"} type={"primary"} onClick={() => this.setState({visible: true, itemId: undefined})}>新增</Button>
            ]} >
                <ProCard>
                <Table
                    size={"small"}
                    columns={columns}
                    dataSource={dataSource}
                />
                </ProCard>
                <FormDrawer visible={visible}
                            itemId={itemId}
                            onClose={() => this.setState({visible: false})}
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
}

export default injection(Books)
