import React from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'

import {trans} from '#/main/core/translation'
import {currentUser} from '#/main/core/user/current'

import {Button} from '#/main/app/action/components/button'
import {Sections, Section} from '#/main/core/layout/components/sections'
import {DataDetails} from '#/main/core/data/details/components/details'
import {DataListContainer} from '#/main/core/data/list/containers/data-list'

import {User as UserTypes} from '#/main/core/user/prop-types'
import {UserAvatar} from '#/main/core/user/components/avatar'
import {actions} from '#/main/core/administration/user/user/actions'

import {OrganizationList} from '#/main/core/administration/user/organization/components/organization-list'
import {GroupList} from '#/main/core/administration/user/group/components/group-list'
import {RoleList} from '#/main/core/administration/user/role/components/role-list'

// todo : maybe merge UserCompare with the content of about modal
// todo : fixes titles level
// todo : merge embedded list with the one from the form if possible

const UserCompare = props =>
  <div className="panel panel-default embedded-details-section">
    <div className="panel-heading text-center">
      <UserAvatar picture={props.user.picture} alt={false} />
      <h3 className="panel-title">{props.user.username}</h3>
    </div>

    <DataDetails
      data={props.user}
      sections={[
        {
          title: trans('general'),
          primary: true,
          fields: [
            {
              name: 'firstName',
              label: trans('firstName'),
              type: 'string'
            }, {
              name: 'lastName',
              label: trans('lastName'),
              type: 'string'
            }, {
              name: 'email',
              label: trans('email'),
              type: 'email'
            }, {
              name: 'meta.created',
              label: trans('creation_date'),
              type: 'date',
              options: {
                time: true
              }
            }, {
              name: 'meta.lastLogin',
              label: trans('last_login'),
              type: 'date',
              options: {
                time: true
              }
            }, {
              name: 'cas_data.id',
              label: trans('cas_id'),
              type: 'string'
            }, {
              name: 'meta.mailValidated',
              label: trans('email_validated'),
              type: 'boolean'
            }, {
              name: 'restrictions.disabled',
              label: trans('user_disabled'),
              type: 'boolean'
            }
          ]
        }
      ]}
    >
      <Sections
        level={3}
      >
        <Section
          className="embedded-list-section"
          icon="fa fa-fw fa-users"
          title={trans('groups')}
        >
          <DataListContainer
            name={`users.compare.groupsUser${props.index}`}
            fetch={{
              url: ['apiv2_user_list_groups', {id: props.user.id}],
              autoload: true
            }}
            definition={GroupList.definition}
            card={GroupList.card}
          />
        </Section>

        <Section
          className="embedded-list-section"
          icon="fa fa-fw fa-building"
          title={trans('organizations')}
        >
          <DataListContainer
            name={`users.compare.organizationsUser${props.index}`}
            fetch={{
              url: ['apiv2_user_list_organizations', {id: props.user.id}],
              autoload: true
            }}
            definition={OrganizationList.definition}
            card={OrganizationList.card}
          />
        </Section>

        <Section
          className="embedded-list-section"
          icon="fa fa-fw fa-id-badge"
          title={trans('roles')}
        >
          <DataListContainer
            name={`users.compare.rolesUser${props.index}`}
            fetch={{
              url: ['apiv2_user_list_roles', {id: props.user.id}],
              autoload: true
            }}
            definition={RoleList.definition}
            card={RoleList.card}
          />
        </Section>
      </Sections>
    </DataDetails>

    <Button
      className="panel-btn btn"
      type="callback"
      primary={true}
      label={trans('keep_user')}
      callback={props.merge}
      disabled={props.disabled}
      confirm={{
        title: trans('merge'),
        message: trans('merge_confirmation', {username: props.user.username}),
        button: trans('merge', {}, 'actions')
      }}
    />
  </div>

UserCompare.propTypes = {
  index: T.number.isRequired,
  disabled: T.bool.isRequired,
  user: T.shape({
    id: T.string.isRequired
  }).isRequired,
  merge: T.func.isRequired
}

const UsersMergeForm = props => 0 !== props.selectedUsers.length ?
  <div className="row">
    <div className="col-md-6">
      <UserCompare
        index={0}
        user={props.selectedUsers[0]}
        merge={() => props.mergeUsers(props.selectedUsers[0], props.selectedUsers[1])}
        disabled={props.selectedUsers[1].id === currentUser().id}
      />
    </div>

    <div className="col-md-6">
      <UserCompare
        index={1}
        user={props.selectedUsers[1]}
        merge={() => props.mergeUsers(props.selectedUsers[1], props.selectedUsers[0])}
        disabled={props.selectedUsers[0].id === currentUser().id}
      />
    </div>
  </div> :
  <div>Loading</div>

UsersMergeForm.propTypes = {
  selectedUsers: T.arrayOf(T.shape(
    UserTypes.propTypes
  )),
  mergeUsers: T.func.isRequired
}

const UsersMerge = connect(
  state => ({
    selectedUsers: state.users.compare.selected
  }),
  dispatch => ({
    mergeUsers(userToKeep, userToRemove) {
      dispatch(actions.merge(userToKeep.id, userToRemove.id))
    }
  })
)(UsersMergeForm)

export {
  UsersMerge
}